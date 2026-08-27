import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

document.addEventListener('DOMContentLoaded', () => {
  initScrollReveal();
  initNavbar();
  initAnchorScroll();
  initCounters();
  initMagnetic();
  initAmbientGlow();
  initTilt();
});

/* ---------------------------------------------------------------------------
   Scroll reveal
   Elements fade up as they enter. Siblings inside [data-reveal-group] are
   staggered automatically so we never hand-write reveal-d1..d6 in markup.
   ------------------------------------------------------------------------- */
function initScrollReveal() {
  const targets = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
  if (!targets.length) return;

  if (prefersReducedMotion || !('IntersectionObserver' in window)) {
    targets.forEach((el) => el.classList.add('is-visible'));
    return;
  }

  // Auto-stagger children of a reveal group
  document.querySelectorAll('[data-reveal-group]').forEach((group) => {
    const step = parseInt(group.dataset.revealGroup, 10) || 80;
    group
      .querySelectorAll(':scope > .reveal, :scope > .reveal-left, :scope > .reveal-right, :scope > .reveal-scale')
      .forEach((child, i) => {
        if (!child.style.transitionDelay) {
          child.style.transitionDelay = `${Math.min(i * step, 640)}ms`;
        }
      });
  });

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    },
    { threshold: 0.06, rootMargin: '0px 0px -8% 0px' }
  );

  targets.forEach((el) => observer.observe(el));
}

/* ---------------------------------------------------------------------------
   Navbar — condenses on scroll
   ------------------------------------------------------------------------- */
function initNavbar() {
  const navbar = document.getElementById('navbar');
  if (!navbar) return;

  let ticking = false;

  const update = () => {
    navbar.classList.toggle('navbar-scrolled', window.scrollY > 24);
    ticking = false;
  };

  update();

  window.addEventListener(
    'scroll',
    () => {
      if (ticking) return;
      requestAnimationFrame(update);
      ticking = true;
    },
    { passive: true }
  );
}

/* ---------------------------------------------------------------------------
   Anchor links offset by the fixed navbar
   ------------------------------------------------------------------------- */
function initAnchorScroll() {
  const navbar = document.getElementById('navbar');

  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (!href || href === '#') return;

      let target;
      try {
        target = document.querySelector(href);
      } catch {
        return;
      }
      if (!target) return;

      e.preventDefault();
      const offset = (navbar ? navbar.offsetHeight : 0) + 24;
      const top = target.getBoundingClientRect().top + window.scrollY - offset;

      window.scrollTo({ top, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
    });
  });
}

/* ---------------------------------------------------------------------------
   Number counters
   ------------------------------------------------------------------------- */
function initCounters() {
  const counters = document.querySelectorAll('[data-counter]');
  if (!counters.length) return;

  const render = (el, value) => {
    const decimals = parseInt(el.dataset.counterDecimals, 10) || 0;
    const prefix = el.dataset.counterPrefix || '';
    const suffix = el.dataset.counterSuffix || '';
    el.textContent = prefix + value.toLocaleString('id-ID', {
      minimumFractionDigits: decimals,
      maximumFractionDigits: decimals,
    }) + suffix;
  };

  if (prefersReducedMotion || !('IntersectionObserver' in window)) {
    counters.forEach((el) => render(el, parseFloat(el.dataset.counter) || 0));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        animateCounter(entry.target, render);
        observer.unobserve(entry.target);
      });
    },
    { threshold: 0.45 }
  );

  counters.forEach((el) => observer.observe(el));
}

function animateCounter(el, render) {
  const target = parseFloat(el.dataset.counter) || 0;
  const duration = parseInt(el.dataset.counterDuration, 10) || 1800;
  const start = performance.now();

  const step = (now) => {
    const progress = Math.min((now - start) / duration, 1);
    // easeOutQuart — decelerates gently, matches the CSS easing
    const eased = 1 - Math.pow(1 - progress, 4);
    render(el, target * eased);
    if (progress < 1) requestAnimationFrame(step);
    else render(el, target);
  };

  requestAnimationFrame(step);
}

/* ---------------------------------------------------------------------------
   Magnetic buttons — very low strength, spring-back on leave
   ------------------------------------------------------------------------- */
function initMagnetic() {
  if (!canHover || prefersReducedMotion) return;

  document.querySelectorAll('.magnetic').forEach((el) => {
    const strength = parseFloat(el.dataset.magnetic) || 0.16;

    el.addEventListener('mousemove', (e) => {
      const rect = el.getBoundingClientRect();
      const x = e.clientX - rect.left - rect.width / 2;
      const y = e.clientY - rect.top - rect.height / 2;
      el.style.transition = 'transform 120ms linear';
      el.style.transform = `translate(${x * strength}px, ${y * strength}px)`;
    });

    el.addEventListener('mouseleave', () => {
      el.style.transition = 'transform 700ms cubic-bezier(0.22, 1, 0.36, 1)';
      el.style.transform = 'translate(0, 0)';
    });
  });
}

/* ---------------------------------------------------------------------------
   Ambient cursor glow — champagne, barely there, desktop only
   ------------------------------------------------------------------------- */
function initAmbientGlow() {
  if (!canHover || prefersReducedMotion) return;
  if (!document.querySelector('[data-ambient-glow]')) return;

  const glow = document.createElement('div');
  glow.setAttribute('aria-hidden', 'true');
  glow.style.cssText = `
    position: fixed; left: 0; top: 0; width: 420px; height: 420px;
    border-radius: 999px; pointer-events: none; z-index: 1; opacity: 0;
    background: radial-gradient(circle, rgba(217,184,124,0.10) 0%, transparent 68%);
    transform: translate(-50%, -50%);
    transition: opacity 700ms cubic-bezier(0.33, 1, 0.68, 1);
    will-change: left, top;
  `;
  document.body.appendChild(glow);

  let raf = null;
  let visible = false;

  document.addEventListener(
    'mousemove',
    (e) => {
      if (raf) return;
      raf = requestAnimationFrame(() => {
        glow.style.left = `${e.clientX}px`;
        glow.style.top = `${e.clientY}px`;
        raf = null;
      });
      if (!visible) {
        visible = true;
        glow.style.opacity = '1';
      }
    },
    { passive: true }
  );

  document.addEventListener('mouseleave', () => {
    visible = false;
    glow.style.opacity = '0';
  });
}

/* ---------------------------------------------------------------------------
   Subtle 3D tilt for hero visuals — max 4deg, always eased back
   ------------------------------------------------------------------------- */
function initTilt() {
  if (!canHover || prefersReducedMotion) return;

  document.querySelectorAll('[data-tilt]').forEach((el) => {
    const max = parseFloat(el.dataset.tilt) || 4;
    el.style.transformStyle = 'preserve-3d';

    el.addEventListener('mousemove', (e) => {
      const rect = el.getBoundingClientRect();
      const px = (e.clientX - rect.left) / rect.width - 0.5;
      const py = (e.clientY - rect.top) / rect.height - 0.5;
      el.style.transition = 'transform 220ms cubic-bezier(0.33, 1, 0.68, 1)';
      el.style.transform = `perspective(1200px) rotateY(${px * max}deg) rotateX(${-py * max}deg)`;
    });

    el.addEventListener('mouseleave', () => {
      el.style.transition = 'transform 900ms cubic-bezier(0.22, 1, 0.36, 1)';
      el.style.transform = 'perspective(1200px) rotateY(0deg) rotateX(0deg)';
    });
  });
}

/* ---------------------------------------------------------------------------
   Toast
   ------------------------------------------------------------------------- */
window.showToast = function (message, type = 'success', duration = 5000) {
  const toast = document.createElement('div');
  toast.className = `toast toast-${type === 'error' ? 'error' : 'success'} fixed top-24 right-6 z-[100]`;
  toast.style.cssText = 'opacity:0;transform:translateY(-10px);transition:opacity 400ms,transform 600ms cubic-bezier(0.22,1,0.36,1);';
  toast.textContent = message;
  document.body.appendChild(toast);

  requestAnimationFrame(() => {
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';
  });

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(-10px)';
    setTimeout(() => toast.remove(), 500);
  }, duration);
};
