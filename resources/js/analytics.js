/*
 * One small analytics surface for the public site. The layout decides whether
 * events are sent to standalone gtag or to GTM's dataLayer; this file never
 * loads a vendor script and therefore cannot create a second tracker.
 */
const ATTRIBUTION_KEY = 'aldeftech_attribution_v1';
const ATTRIBUTION_FIELDS = [
  'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
  'gclid', 'gbraid', 'wbraid', 'fbclid', 'landing_page', 'referrer',
];
const CAMPAIGN_FIELDS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'gbraid', 'wbraid', 'fbclid'];

function storage() {
  try {
    const key = '__aldeftech_storage_test__';
    window.localStorage.setItem(key, '1');
    window.localStorage.removeItem(key);
    return window.localStorage;
  } catch {
    return null;
  }
}

function clean(value, max = 160) {
  return String(value ?? '').replace(/[\u0000-\u001f\u007f]/g, '').trim().slice(0, max);
}

function readAttribution() {
  const store = storage();
  let saved = {};

  try {
    saved = JSON.parse(store?.getItem(ATTRIBUTION_KEY) || '{}');
  } catch {
    saved = {};
  }

  const params = new URLSearchParams(window.location.search);
  const incoming = {};
  ATTRIBUTION_FIELDS.forEach((field) => {
    const value = clean(params.get(field));
    if (value) incoming[field] = value;
  });

  const attribution = { ...saved };
  CAMPAIGN_FIELDS.forEach((field) => {
    // First touch wins. A later campaign must not overwrite the original lead
    // source, while the session can still be inspected in GA4 automatically.
    if (!attribution[field] && incoming[field]) attribution[field] = incoming[field];
  });

  if (!attribution.landing_page) attribution.landing_page = clean(window.location.pathname, 500);
  if (!attribution.referrer && document.referrer) attribution.referrer = clean(document.referrer, 500);

  if (store) {
    try { store.setItem(ATTRIBUTION_KEY, JSON.stringify(attribution)); } catch { /* private browsing */ }
  }

  return attribution;
}

function pageParams() {
  return {
    page_path: clean(window.location.pathname, 500),
    page_title: clean(document.title, 160),
    language: clean(document.documentElement.lang || 'id', 12),
  };
}

function eventParams(element = null, extra = {}) {
  const data = element?.dataset || {};
  const params = {
    ...pageParams(),
    cta_location: clean(data.analyticsCtaLocation, 80),
    service: clean(data.analyticsService, 160),
    portfolio_slug: clean(data.analyticsPortfolioSlug, 160),
    destination: clean(data.analyticsDestination, 40),
    form_name: clean(data.analyticsFormName, 80),
    ...extra,
  };

  return Object.fromEntries(Object.entries(params).filter(([, value]) => value !== ''));
}

function send(name, params = {}) {
  const analytics = window.__aldefAnalytics || {};
  const payload = Object.fromEntries(Object.entries(params).filter(([, value]) => value !== '' && value != null));

  try {
    if (analytics.mode === 'gtag' && typeof window.gtag === 'function') {
      window.gtag('event', name, payload);
    } else if (analytics.mode === 'gtm' && Array.isArray(window.dataLayer)) {
      window.dataLayer.push({ event: name, ...payload });
    } else if (analytics.debug && window.console) {
      console.debug('[analytics]', name, payload);
    }
  } catch {
    // Tracking must never break navigation, forms, or the mobile menu.
  }
}

function once(key) {
  const store = storage();
  if (!store) return true;
  try {
    if (store.getItem(key)) return false;
    store.setItem(key, '1');
  } catch {
    return true;
  }
  return true;
}

function initAttribution() {
  const attribution = readAttribution();
  document.querySelectorAll('[data-attribution-field]').forEach((field) => {
    field.value = attribution[field.dataset.attributionField] || '';
  });
}

function initLeadConversion() {
  const conversion = window.__aldefLeadConversion;
  if (!conversion?.id || !once('aldeftech_generate_lead_' + conversion.id)) return;

  send('generate_lead', {
    ...pageParams(),
    language: clean(document.documentElement.lang || 'id', 12),
    lead_source: clean(conversion.lead_source || 'website', 40),
    form_name: clean(conversion.form_name || 'project_brief', 80),
    project_type: clean(conversion.project_type, 100),
    budget_range: clean(conversion.budget_range, 100),
    ...Object.fromEntries(CAMPAIGN_FIELDS.map((field) => [field, clean(conversion[field], 160)])),
  });
}

function initFormTracking() {
  document.querySelectorAll('form[data-analytics-form]').forEach((form) => {
    let started = false;
    form.addEventListener('focusin', () => {
      if (started) return;
      started = true;
      send('contact_form_start', eventParams(form, { form_name: form.dataset.analyticsForm }));
    });
    form.addEventListener('submit', (event) => {
      if (form.dataset.analyticsSubmitting === 'true') {
        event.preventDefault();
        return;
      }
      form.dataset.analyticsSubmitting = 'true';
      send('contact_form_submit', eventParams(form, { form_name: form.dataset.analyticsForm }));
      const button = form.querySelector('button[type="submit"]');
      if (button) button.setAttribute('aria-busy', 'true');
    });
  });
}

function initClickTracking() {
  document.addEventListener('click', (event) => {
    const element = event.target.closest?.('a, button');
    if (!element) return;

    const href = element.getAttribute('href') || '';
    const explicit = element.dataset.analyticsEvent;
    const params = eventParams(element);

    if (explicit) send(explicit, params);
    if (element.dataset.analyticsAlsoEvent) send(element.dataset.analyticsAlsoEvent, params);

    if (!explicit && /^https:\/\/wa\.me\//i.test(href)) send('whatsapp_click', params);
    if (!explicit && /^mailto:/i.test(href)) send('email_click', params);
  }, { passive: true });
}

function initPageViewEvents() {
  const pageType = document.body.dataset.analyticsPageType;
  const item = document.body.dataset.analyticsItem;
  if (pageType === 'service') send('view_service', { ...pageParams(), service: clean(item) });
  if (pageType === 'portfolio') send('view_portfolio', pageParams());
  if (pageType === 'case-study') send('view_case_study', { ...pageParams(), portfolio_slug: clean(item) });
}

document.addEventListener('DOMContentLoaded', () => {
  initAttribution();
  initLeadConversion();
  initFormTracking();
  initClickTracking();
  initPageViewEvents();
});

window.aldefAnalytics = { track: send, attribution: readAttribution };
