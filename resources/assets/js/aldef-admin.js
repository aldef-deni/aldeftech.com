/**
 * Aldef Tech admin console behaviours.
 * Small, dependency-free helpers shared by every CRUD screen.
 */

document.addEventListener('DOMContentLoaded', function () {
  initImagePreview();
  initSlugFrom();
  initRepeater();
  initAutoDismissAlerts();
});

/* Live preview when picking an image in <x-admin.form.image> */
function initImagePreview() {
  document.querySelectorAll('[data-image-field] input[type="file"]').forEach(function (input) {
    input.addEventListener('change', function () {
      const field = input.closest('[data-image-field]');
      const preview = field && field.querySelector('[data-image-preview]');
      const file = input.files && input.files[0];
      if (!preview || !file) return;

      const url = URL.createObjectURL(file);
      preview.src = url;
      preview.style.display = '';
      preview.addEventListener('load', () => URL.revokeObjectURL(url), { once: true });
    });
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
