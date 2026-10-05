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

function clickLocation(element) {
  if (element.dataset.analyticsCtaLocation) return clean(element.dataset.analyticsCtaLocation, 80);
  if (element.closest('#mobile-drawer')) return 'navbar_mobile';
  if (element.closest('#navbar')) return 'navbar';
  if (element.closest('footer')) return 'footer';
  if (element.closest('form')) return 'contact_form';

  const section = element.closest('article[id], section[id]');
  return clean(section?.id || 'page_content', 80);
}

function clickParams(element) {
  const label = element.querySelector('h2, h3, span');
  return eventParams(element, {
    cta_name: clean(element.dataset.analyticsCtaName || element.getAttribute('aria-label')
      || label?.textContent || element.textContent, 100).replace(/\s+/g, ' '),
    cta_location: clickLocation(element),
  });
}

function linkTarget(element) {
  try {
    return new URL(element.getAttribute('href'), window.location.href);
  } catch {
    return null;
  }
}

function isWhatsApp(target) {
  return target && (target.protocol === 'whatsapp:'
    || (['https:', 'http:'].includes(target.protocol)
      && ['wa.me', 'www.wa.me', 'api.whatsapp.com', 'web.whatsapp.com', 'whatsapp.com', 'www.whatsapp.com'].includes(target.hostname)));
}

function isPublicCta(element, target) {
  if (!target || target.origin !== window.location.origin) return false;
  if (!/^\/(?:en\/)?(?:contact|services|solutions|portfolio|blog)(?:\/|$)/.test(target.pathname)) return false;
  return element.matches('.btn, .link-arrow, .card-obsidian, .card-lux, .card-quiet')
    || /^\/(?:en\/)?contact\/?$/.test(target.pathname);
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
    page_path: clean(conversion.page_path || window.location.pathname, 500),
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
  const trackClick = (event) => {
    if (event.type === 'auxclick' && event.button !== 1) return;
    const element = event.target?.closest?.('a, button');
    if (!element) return;

    const href = element.getAttribute('href') || '';
    const target = href ? linkTarget(element) : null;
    const whatsapp = isWhatsApp(target);
    const params = clickParams(element);
    const events = new Set([
      element.dataset.analyticsEvent,
      element.dataset.analyticsAlsoEvent,
    ].filter(Boolean));

    if (whatsapp) {
      events.add('whatsapp_click');
      events.add('cta_click');
      params.button_location = params.cta_location;
      params.destination = 'whatsapp';
    } else if (isPublicCta(element, target)) {
      events.add('cta_click');
    }
    if (/^mailto:/i.test(href)) events.add('email_click');

    events.forEach((name) => send(name, params));
  };

  // Capture queues events before link handlers/navigation. Native navigation
  // stays immediate, including new tabs and WhatsApp app links.
  document.addEventListener('click', trackClick, { capture: true, passive: true });
  document.addEventListener('auxclick', trackClick, { capture: true, passive: true });
}

function initPageViewEvents() {
  const pageType = document.body.dataset.analyticsPageType;
  const item = document.body.dataset.analyticsItem;
  if (pageType === 'service') send('view_service', { ...pageParams(), service: clean(item) });
  if (pageType === 'portfolio') send('view_portfolio', pageParams());
  if (pageType === 'case-study') send('view_case_study', { ...pageParams(), portfolio_slug: clean(item) });
}

document.addEventListener('DOMContentLoaded', () => {
  if (/^\/(?:en\/)?admin(?:\/|$)/.test(window.location.pathname)) return;
  initAttribution();
  initLeadConversion();
  initFormTracking();
  initClickTracking();
  initPageViewEvents();
});

window.aldefAnalytics = { track: send, attribution: readAttribution };
