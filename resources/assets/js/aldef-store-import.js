/**
 * Aldef Store ZIP import. The server works in short steps, so the browser
 * drives each phase in a loop: upload chunks → extract → (preview) → import.
 */
const token = document.querySelector('meta[name="csrf-token"]');

document.addEventListener('DOMContentLoaded', function () {
  const uploader = document.querySelector('[data-store-import]');
  if (uploader) initUploader(uploader);

  const runner = document.querySelector('[data-import-runner]');
  if (runner) initRunner(runner);
});

async function post(url, body) {
  const res = await fetch(url, {
    method: 'POST',
    headers: Object.assign(
      { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      token ? { 'X-CSRF-TOKEN': token.content } : {},
      body instanceof FormData ? {} : { 'Content-Type': 'application/json' },
    ),
    body: body instanceof FormData ? body : JSON.stringify(body || {}),
  });
  let data = {};
  try { data = await res.json(); } catch { /* not JSON */ }
  if (!res.ok) {
    const error = new Error(data.message || 'Permintaan gagal (' + res.status + ').');
    error.status = res.status;
    error.data = data;
    throw error;
  }
  return data;
}

const wait = (ms) => new Promise((r) => setTimeout(r, ms));

function formatMb(bytes) {
  return (bytes / 1048576).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + ' MB';
}

/* ── Upload page ──────────────────────────────────────────────────────────── */

function initUploader(root) {
  const fileInput = root.querySelector('[data-import-file]');
  const pick = root.querySelector('[data-import-pick]');
  const progress = root.querySelector('[data-import-progress]');
  const bar = root.querySelector('[data-import-bar]');
  const stage = root.querySelector('[data-import-stage]');
  const percent = root.querySelector('[data-import-percent]');
  const detail = root.querySelector('[data-import-detail]');
  const errorBox = root.querySelector('[data-import-error]');
  const chunkBytes = parseInt(root.dataset.chunkBytes, 10);
  const maxBytes = parseInt(root.dataset.maxBytes, 10);
  let busy = false;

  const show = function (label, pct, text) {
    progress.hidden = false;
    stage.textContent = label;
    percent.textContent = Math.floor(pct) + '%';
    bar.style.width = pct + '%';
    detail.textContent = text || '';
  };
  const fail = function (message) {
    errorBox.textContent = message;
    errorBox.hidden = false;
    bar.classList.remove('progress-bar-animated');
    busy = false;
    pick.disabled = false;
  };

  pick.addEventListener('click', () => { if (!busy) fileInput.click(); });
  fileInput.addEventListener('change', () => { if (fileInput.files[0]) begin(fileInput.files[0]); fileInput.value = ''; });
  ['dragenter', 'dragover'].forEach((type) => pick.addEventListener(type, (e) => { e.preventDefault(); pick.classList.add('border-primary'); }));
  ['dragleave', 'drop'].forEach((type) => pick.addEventListener(type, (e) => { e.preventDefault(); pick.classList.remove('border-primary'); }));
  pick.addEventListener('drop', (e) => { if (!busy && e.dataTransfer.files[0]) begin(e.dataTransfer.files[0]); });

  // Leaving mid-upload loses the work; ask first.
  window.addEventListener('beforeunload', (e) => { if (busy) { e.preventDefault(); e.returnValue = ''; } });

  async function begin(file) {
    errorBox.hidden = true;
    if (!/\.zip$/i.test(file.name)) return fail('Pilih berkas berformat .zip.');
    if (file.size > maxBytes) return fail('Ukuran ZIP ' + formatMb(file.size) + ' melebihi batas ' + formatMb(maxBytes) + '.');

    busy = true;
    pick.disabled = true;
    bar.classList.add('progress-bar-animated');

    let session;
    try {
      session = await post(root.dataset.startUrl, { name: file.name, size: file.size });
    } catch (e) {
      return fail(e.message);
    }

    // Upload chunks in order. A failed chunk is retried; if the server says
    // the order is off, continue from what it actually has.
    let offset = 0;
    while (offset < file.size) {
      const chunk = file.slice(offset, offset + chunkBytes);
      let attempt = 0;
      for (;;) {
        try {
          const form = new FormData();
          form.append('offset', String(offset));
          form.append('chunk', chunk, 'chunk.bin');
          const res = await post(session.chunk_url, form);
          offset = res.received;
          break;
        } catch (e) {
          if (e.status === 409 && e.data && typeof e.data.received === 'number' && e.data.received !== offset) {
            offset = e.data.received;
            break;
          }
          if (++attempt >= 3) return fail('Unggahan terhenti: ' + e.message);
          await wait(1500 * attempt);
        }
      }
      show('Mengunggah ZIP…', (offset / file.size) * 100, formatMb(offset) + ' dari ' + formatMb(file.size));
    }

    // Extract in steps until the preview is ready.
    try {
      for (;;) {
        const res = await post(session.extract_url);
        if (res.status === 'failed') return fail(res.message || 'Ekstraksi gagal.');
        if (res.status === 'ready') break;
        show('Mengekstrak dan memeriksa berkas…', res.total ? (res.done / res.total) * 100 : 0,
          res.done + ' dari ' + res.total + ' berkas di dalam ZIP');
      }
    } catch (e) {
      return fail('Ekstraksi terhenti: ' + e.message + ' Buka import ini dari Riwayat untuk melanjutkan.');
    }

    busy = false;
    show('Selesai diperiksa. Membuka pratinjau…', 100);
    window.location = session.show_url;
  }
}

/* ── Preview / progress page ─────────────────────────────────────────────── */

function initRunner(root) {
  const start = root.querySelector('[data-runner-start]');
  const startBox = root.querySelector('[data-runner-start-box]');
  const progress = root.querySelector('[data-runner-progress]');
  const stage = root.querySelector('[data-runner-stage]');
  const bar = root.querySelector('[data-runner-bar]');
  const percent = root.querySelector('[data-runner-percent]');
  const errorBox = root.querySelector('[data-runner-error]');
  let busy = false;

  window.addEventListener('beforeunload', (e) => { if (busy) { e.preventDefault(); e.returnValue = ''; } });

  const show = function (label, done, total) {
    progress.hidden = false;
    stage.textContent = label;
    const pct = total ? (done / total) * 100 : 0;
    bar.style.width = pct + '%';
    percent.textContent = Math.floor(pct) + '%';
  };

  async function loop(url, label, finished) {
    busy = true;
    errorBox.hidden = true;
    let failures = 0;
    for (;;) {
      try {
        const res = await post(url);
        failures = 0;
        if (res.status === 'failed') throw new Error(res.message || 'Proses gagal.');
        show(label + ' (' + res.done + ' dari ' + res.total + ')', res.done, res.total);
        if (finished(res.status)) break;
      } catch (e) {
        // A dropped request is retried; the server resumes where it stopped.
        if (++failures >= 3) {
          busy = false;
          errorBox.textContent = e.message + ' Muat ulang halaman ini untuk melanjutkan.';
          errorBox.hidden = false;
          return;
        }
        await wait(2000 * failures);
      }
    }
    busy = false;
    window.location.reload();
  }

  if (root.dataset.status === 'extracting') {
    loop(root.dataset.extractUrl, 'Mengekstrak ZIP', (status) => status !== 'extracting');
  } else if (root.dataset.status === 'importing') {
    loop(root.dataset.runUrl, 'Membuat produk', (status) => status === 'done');
  }

  if (start) {
    start.addEventListener('click', function () {
      start.disabled = true;
      if (startBox) startBox.hidden = true;
      loop(root.dataset.runUrl, 'Membuat produk', (status) => status === 'done');
    });
  }
}
