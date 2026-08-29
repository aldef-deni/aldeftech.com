/**
 * Aldef Tech admin console behaviours.
 * Small, dependency-free helpers shared by every CRUD screen.
 */

document.addEventListener('DOMContentLoaded', function () {
  initImageUploader();
  initSlugFrom();
  initRepeater();
  initAutoDismissAlerts();
  initSeoPreview();
});

/**
 * Inline image uploader for <x-admin.form.image>.
 *
 * The file goes up on its own the moment it is chosen, and the returned path
 * lands in the field's hidden input — so the surrounding form still submits a
 * plain string and no controller had to learn about file handling.
 */
function initImageUploader() {
  const token = document.querySelector('meta[name="csrf-token"]');

  document.querySelectorAll('[data-uploader]').forEach(function (root) {
    const file = root.querySelector('[data-uploader-file]');
    const value = root.querySelector('[data-uploader-value]');
    const preview = root.querySelector('[data-uploader-preview]');
    const empty = root.querySelector('.aldef-uploader-empty');
    const actions = root.querySelector('[data-uploader-actions]');
    const veil = root.querySelector('[data-uploader-veil]');
    const bar = root.querySelector('[data-uploader-bar]');
    const fill = bar && bar.querySelector('span');
    const nameOut = root.querySelector('[data-uploader-name]');
    const errorOut = root.querySelector('[data-uploader-error]');
    if (!file || !value) return;

    const url = root.dataset.uploaderUrl;

    const showError = (message) => {
      if (!errorOut) return;
      errorOut.textContent = message;
      errorOut.hidden = false;
    };
    const clearError = () => { if (errorOut) errorOut.hidden = true; };

    const paint = (src, label) => {
      root.classList.toggle('has-image', Boolean(src));
      if (preview) {
        preview.src = src || '';
        preview.style.display = src ? '' : 'none';
      }
      if (empty) empty.style.display = src ? 'none' : '';
      if (actions) actions.style.display = src ? '' : 'none';
      if (nameOut) nameOut.textContent = label || '';
    };

    root.querySelectorAll('[data-uploader-trigger]').forEach(function (btn) {
      btn.addEventListener('click', function () { file.click(); });
    });

    const clearBtn = root.querySelector('[data-uploader-clear]');
    if (clearBtn) {
      clearBtn.addEventListener('click', function () {
        // Only drops the reference. The stored file is left alone — another
        // record may well be pointing at it.
        value.value = '';
        file.value = '';
        clearError();
        paint('', '');
      });
    }

    file.addEventListener('change', function () {
      if (file.files && file.files[0]) upload(file.files[0]);
    });

    // Drag and drop over the whole field
    ['dragenter', 'dragover'].forEach(function (type) {
      root.addEventListener(type, function (e) {
        e.preventDefault();
        root.classList.add('is-dragging');
      });
    });
    ['dragleave', 'drop'].forEach(function (type) {
      root.addEventListener(type, function (e) {
        e.preventDefault();
        if (type === 'dragleave' && root.contains(e.relatedTarget)) return;
        root.classList.remove('is-dragging');
      });
    });
    root.addEventListener('drop', function (e) {
      const dropped = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0];
      if (dropped) upload(dropped);
    });

    function upload(blob) {
      clearError();

      // Remember what was on screen so a failed upload can be undone.
      const prevSrc = value.value && preview ? preview.src : '';
      const prevName = nameOut ? nameOut.textContent : '';

      // Show the local file straight away; the round trip only confirms it.
      const localUrl = URL.createObjectURL(blob);
      paint(localUrl, blob.name);

      if (veil) veil.hidden = false;
      if (bar) { bar.hidden = false; if (fill) fill.style.width = '0%'; }

      const body = new FormData();
      body.append('file', blob);

      const xhr = new XMLHttpRequest();
      xhr.open('POST', url);
      xhr.setRequestHeader('Accept', 'application/json');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      if (token) xhr.setRequestHeader('X-CSRF-TOKEN', token.content);

      xhr.upload.addEventListener('progress', function (e) {
        if (fill && e.lengthComputable) {
          fill.style.width = Math.round((e.loaded / e.total) * 100) + '%';
        }
      });

      const finish = () => {
        if (veil) veil.hidden = true;
        if (bar) bar.hidden = true;
        URL.revokeObjectURL(localUrl);
      };

      xhr.addEventListener('load', function () {
        finish();
        let data = {};
        try { data = JSON.parse(xhr.responseText); } catch (e) { /* non-JSON error page */ }

        if (xhr.status >= 200 && xhr.status < 300 && data.path) {
          value.value = data.path;
          paint(data.url, data.name);
          return;
        }

        paint(prevSrc, prevName);
        showError(data.message || 'Berkas gagal diunggah (kode ' + xhr.status + ').');
        file.value = '';
      });

      xhr.addEventListener('error', function () {
        finish();
        paint(prevSrc, prevName);
        showError('Koneksi terputus saat mengunggah. Silakan coba lagi.');
        file.value = '';
      });

      xhr.send(body);
    }
  });
}

/* Mirror a title field into a slug field until the slug is edited by hand */
function initSlugFrom() {
  document.querySelectorAll('[data-slug-from]').forEach(function (slugInput) {
    const source = document.querySelector(slugInput.dataset.slugFrom);
    if (!source) return;

    // Respect a slug that already exists (editing) or that the user types.
    let locked = slugInput.value.trim() !== '';
    slugInput.addEventListener('input', () => { locked = true; });

    source.addEventListener('input', function () {
      if (locked) return;
      slugInput.value = source.value
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    });
  });
}

/**
 * Repeatable text rows for list-shaped columns (features, skills, technologies).
 * Markup: [data-repeater] > [data-repeater-list] > [data-repeater-item]
 */
function initRepeater() {
  document.querySelectorAll('[data-repeater]').forEach(function (repeater) {
    const list = repeater.querySelector('[data-repeater-list]');
    const addBtn = repeater.querySelector('[data-repeater-add]');
    if (!list || !addBtn) return;

    const template = list.querySelector('[data-repeater-item]');
    if (!template) return;
    const blank = template.cloneNode(true);
    blank.querySelectorAll('input, textarea').forEach((el) => { el.value = ''; });

    addBtn.addEventListener('click', function () {
      const row = blank.cloneNode(true);
      row.querySelectorAll('input, textarea').forEach((el) => { el.value = ''; });
      list.appendChild(row);
      const input = row.querySelector('input, textarea');
      if (input) input.focus();
    });

    list.addEventListener('click', function (e) {
      const remove = e.target.closest('[data-repeater-remove]');
      if (!remove) return;
      const rows = list.querySelectorAll('[data-repeater-item]');
      if (rows.length > 1) {
        remove.closest('[data-repeater-item]').remove();
      } else {
        rows[0].querySelectorAll('input, textarea').forEach((el) => { el.value = ''; });
      }
    });
  });
}

/* Success alerts fade themselves out; errors stay until dismissed. */
function initAutoDismissAlerts() {
  document.querySelectorAll('.alert-success[role="alert"]').forEach(function (alert) {
    setTimeout(function () {
      if (window.bootstrap && window.bootstrap.Alert) {
        window.bootstrap.Alert.getOrCreateInstance(alert).close();
      } else {
        alert.remove();
      }
    }, 6000);
  });
}

/**
 * Character counters and a rough search-result preview.
 *
 * The counter turns amber past the ideal length rather than blocking: Google
 * truncates, it does not reject, and a slightly long title is sometimes the
 * right call. Showing the consequence beats enforcing a limit.
 */
function initSeoPreview() {
  document.querySelectorAll('[data-seo-preview]').forEach(function (card) {
    const title = card.querySelector('[data-seo-title]');
    const desc = card.querySelector('[data-seo-desc]');
    const countTitle = card.querySelector('[data-seo-count-title]');
    const countDesc = card.querySelector('[data-seo-count-desc]');
    const serpTitle = card.querySelector('[data-seo-serp-title]');
    const serpDesc = card.querySelector('[data-seo-serp-desc]');

    const wire = (input, counter, serp, placeholder) => {
      if (!input) return;
      const ideal = parseInt(input.dataset.seoIdeal, 10) || 60;

      const sync = () => {
        const len = input.value.length;
        if (counter) {
          counter.textContent = len + ' / ' + ideal;
          counter.classList.toggle('text-warning', len > ideal);
        }
        if (serp) serp.textContent = input.value.trim() || placeholder;
      };

      input.addEventListener('input', sync);
      sync();
    };

    wire(title, countTitle, serpTitle, 'Memakai judul bawaan halaman');
    wire(desc, countDesc, serpDesc, 'Memakai deskripsi bawaan halaman');
  });
}
