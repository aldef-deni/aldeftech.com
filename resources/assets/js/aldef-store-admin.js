/**
 * Aldef Store admin: product photo gallery and the size × colour availability
 * table. Loaded only on the product create/edit screens.
 */
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-store-gallery]').forEach(initGallery);
  document.querySelectorAll('[data-variant-matrix]').forEach(initVariantMatrix);
});

const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

/* ── Gallery ─────────────────────────────────────────────────────────────── */

function initGallery(root) {
  const list = root.querySelector('[data-gallery-list]');
  const fileInput = root.querySelector('[data-gallery-file]');
  const addBtn = root.querySelector('[data-gallery-add]');
  const errorBox = root.querySelector('[data-gallery-error]');
  const max = parseInt(root.dataset.max || '12', 10);
  const token = document.querySelector('meta[name="csrf-token"]');

  const items = () => Array.from(list.querySelectorAll('[data-gallery-item]'));

  function showError(message) {
    errorBox.textContent = message || '';
    errorBox.hidden = !message;
  }

  // Field names carry the position, so they are rewritten after every change.
  function reindex() {
    const all = items();
    let checked = false;
    all.forEach(function (item, i) {
      item.querySelector('[data-gallery-path]').name = 'images[' + i + '][path]';
      item.querySelector('[data-gallery-alt]').name = 'images[' + i + '][alt]';
      const radio = item.querySelector('[data-gallery-primary]');
      radio.value = String(i);
      radio.id = 'primary_' + i;
      radio.nextElementSibling.htmlFor = radio.id;
      checked = checked || radio.checked;
    });
    if (!checked && all.length) all[0].querySelector('[data-gallery-primary]').checked = true;
    addBtn.disabled = all.length >= max;
  }

  function buildItem(path, url) {
    const col = document.createElement('div');
    col.className = 'col-6 col-md-4 col-xl-3';
    col.setAttribute('data-gallery-item', '');
    col.innerHTML =
      '<div class="border rounded p-2 h-100 d-flex flex-column gap-2">' +
      '<div class="ratio ratio-1x1 bg-white rounded border"><img alt="" style="object-fit: contain;" data-gallery-preview></div>' +
      '<input type="hidden" data-gallery-path>' +
      '<input type="text" data-gallery-alt class="form-control form-control-sm" placeholder="Teks alt (opsional)" maxlength="255">' +
      '<div class="form-check mb-0"><input class="form-check-input" type="radio" name="primary_image" data-gallery-primary>' +
      '<label class="form-check-label small">Foto utama</label></div>' +
      '<div class="d-flex gap-1 mt-auto">' +
      '<button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-gallery-up title="Geser ke kiri"><i class="icon-base ti tabler-arrow-up"></i></button>' +
      '<button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-gallery-down title="Geser ke kanan"><i class="icon-base ti tabler-arrow-down"></i></button>' +
      '<button type="button" class="btn btn-sm btn-icon btn-outline-danger ms-auto" data-gallery-remove title="Hapus foto"><i class="icon-base ti tabler-trash"></i></button>' +
      '</div></div>';
    col.querySelector('[data-gallery-preview]').src = url;
    col.querySelector('[data-gallery-path]').value = path;
    return col;
  }

  function upload(file) {
    return new Promise(function (resolve, reject) {
      const body = new FormData();
      body.append('file', file);
      const xhr = new XMLHttpRequest();
      xhr.open('POST', root.dataset.uploadUrl);
      xhr.setRequestHeader('Accept', 'application/json');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      if (token) xhr.setRequestHeader('X-CSRF-TOKEN', token.content);
      xhr.addEventListener('load', function () {
        let data = {};
        try { data = JSON.parse(xhr.responseText || '{}'); } catch { /* not JSON */ }
        if (xhr.status >= 200 && xhr.status < 300 && data.path) resolve(data);
        else reject(new Error(data.message || 'Unggahan gagal (' + xhr.status + ').'));
      });
      xhr.addEventListener('error', function () { reject(new Error('Koneksi terputus saat mengunggah.')); });
      xhr.send(body);
    });
  }

  async function addFiles(files) {
    showError('');
    const room = max - items().length;
    const queue = Array.from(files).slice(0, Math.max(room, 0));
    const problems = [];
    if (files.length > room) problems.push('Maksimal ' + max + ' foto per produk.');

    addBtn.disabled = true;
    for (let i = 0; i < queue.length; i++) {
      const file = queue[i];
      const ext = (file.name.split('.').pop() || '').toLowerCase();
      if (IMAGE_EXTENSIONS.indexOf(ext) === -1) {
        problems.push(file.name + ': format harus JPG, PNG, atau WebP.');
        continue;
      }
      addBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengunggah ' + (i + 1) + '/' + queue.length + '…';
      try {
        const data = await upload(file);
        list.appendChild(buildItem(data.path, data.url));
        reindex();
      } catch (err) {
        problems.push(file.name + ': ' + err.message);
      }
    }
    addBtn.innerHTML = '<i class="icon-base ti tabler-photo-plus me-2"></i>Tambah foto';
    reindex();
    showError(problems.join(' '));
  }

  addBtn.addEventListener('click', function () { fileInput.click(); });
  fileInput.addEventListener('change', function () {
    if (fileInput.files.length) addFiles(fileInput.files);
    fileInput.value = '';
  });

  list.addEventListener('click', function (e) {
    const item = e.target.closest('[data-gallery-item]');
    if (!item) return;
    if (e.target.closest('[data-gallery-remove]')) {
      item.remove();
    } else if (e.target.closest('[data-gallery-up]') && item.previousElementSibling) {
      list.insertBefore(item, item.previousElementSibling);
    } else if (e.target.closest('[data-gallery-down]') && item.nextElementSibling) {
      list.insertBefore(item.nextElementSibling, item);
    } else {
      return;
    }
    reindex();
  });

  reindex();
}

/* ── Size × colour availability ───────────────────────────────────────────── */

function initVariantMatrix(root) {
  const form = root.closest('form');
  const sizesBox = form.querySelector('[data-variant-sizes]');
  const colorsBox = form.querySelector('[data-variant-colors]');

  // Known state per "size|color". null from the server means a new product:
  // every combination starts in stock.
  const initial = JSON.parse(root.dataset.available || 'null');
  const known = JSON.parse(root.dataset.known || '[]');
  const state = new Map();
  if (initial !== null) {
    known.forEach(function (key) { state.set(key, false); });
    initial.forEach(function (key) { state.set(key, true); });
  }

  const values = function (box, name) {
    const out = [];
    box.querySelectorAll('input[name="' + name + '[]"]').forEach(function (input) {
      const v = input.value.trim();
      if (v !== '' && out.indexOf(v) === -1) out.push(v);
    });
    return out;
  };

  function remember() {
    root.querySelectorAll('input[name="available[]"]').forEach(function (box) {
      state.set(box.value, box.checked);
    });
  }

  function render() {
    remember();
    const sizes = values(sizesBox, 'sizes');
    const colors = values(colorsBox, 'colors');
    root.textContent = '';

    if (!sizes.length && !colors.length) {
      const note = document.createElement('div');
      note.className = 'text-body-secondary small';
      note.textContent = 'Isi daftar ukuran atau warna untuk menampilkan tabel ketersediaan.';
      root.appendChild(note);
      return;
    }

    const cols = sizes.length ? sizes : [''];
    const rows = colors.length ? colors : [''];

    const wrap = document.createElement('div');
    wrap.className = 'table-responsive border rounded';
    const table = document.createElement('table');
    table.className = 'table table-sm mb-0 text-center align-middle';

    const head = table.createTHead().insertRow();
    const corner = document.createElement('th');
    corner.className = 'text-start';
    corner.textContent = colors.length ? 'Warna \\ Ukuran' : '';
    head.appendChild(corner);
    cols.forEach(function (size) {
      const th = document.createElement('th');
      th.textContent = size || 'Tersedia';
      head.appendChild(th);
    });

    const body = table.createTBody();
    rows.forEach(function (color) {
      const tr = body.insertRow();
      const label = document.createElement('th');
      label.className = 'text-start fw-medium';
      label.textContent = color || (sizes.length ? 'Tersedia' : '');
      tr.appendChild(label);
      cols.forEach(function (size) {
        const key = size + '|' + color;
        const td = tr.insertCell();
        const box = document.createElement('input');
        box.type = 'checkbox';
        box.className = 'form-check-input';
        box.name = 'available[]';
        box.value = key;
        box.checked = state.has(key) ? state.get(key) : true;
        box.setAttribute('aria-label', [color, size].filter(Boolean).join(' / ') + ' tersedia');
        td.appendChild(box);
      });
    });

    wrap.appendChild(table);
    root.appendChild(wrap);

    const tools = document.createElement('div');
    tools.className = 'd-flex gap-2 mt-2';
    [['Centang semua', true], ['Tandai semua habis', false]].forEach(function (pair) {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'btn btn-sm btn-outline-secondary';
      btn.textContent = pair[0];
      btn.addEventListener('click', function () {
        root.querySelectorAll('input[name="available[]"]').forEach(function (b) { b.checked = pair[1]; });
      });
      tools.appendChild(btn);
    });
    root.appendChild(tools);
  }

  let timer = null;
  const schedule = function () {
    clearTimeout(timer);
    timer = setTimeout(render, 150);
  };

  [sizesBox, colorsBox].forEach(function (box) {
    box.addEventListener('input', schedule);
    // Removing the last row clears it in place, which fires no input event.
    box.addEventListener('click', schedule);
    new MutationObserver(schedule).observe(box, { childList: true, subtree: true });
  });

  // Tells the server the table was submitted, so an unticked box means "out of
  // stock". Without JavaScript the table never renders and existing states
  // are kept instead of every combination silently turning unavailable.
  const flag = document.createElement('input');
  flag.type = 'hidden';
  flag.name = 'variant_matrix';
  flag.value = '1';
  form.appendChild(flag);

  render();
}
