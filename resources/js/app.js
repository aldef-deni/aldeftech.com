import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    // ============================================================
    // SCROLL REVEAL — Intersection Observer
    // ============================================================
    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');

    if (revealElements.length > 0) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.08,
            rootMargin: '0px 0px -40px 0px'
        });

        revealElements.forEach(el => revealObserver.observe(el));
    }

    // ============================================================
    // NAVBAR — Scroll behavior with smooth transitions
    // ============================================================
    const navbar = document.getElementById('navbar');

    if (navbar) {
        let lastScroll = 0;
        let ticking = false;

        const updateNavbar = () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 60) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }

            lastScroll = currentScroll;
            ticking = false;
        };

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        }, { passive: true });
    }

    // ============================================================
    // SMOOTH SCROLL — Anchor links
    // ============================================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const navHeight = navbar ? navbar.offsetHeight : 0;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navHeight - 20;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ============================================================
    // COUNTER ANIMATION
    // ============================================================
    const counters = document.querySelectorAll('[data-counter]');

    if (counters.length > 0) {
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = parseInt(entry.target.getAttribute('data-counter'));
                    const suffix = entry.target.getAttribute('data-counter-suffix') || '';
                    const prefix = entry.target.getAttribute('data-counter-prefix') || '';
                    animateCounter(entry.target, target, prefix, suffix);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(el => counterObserver.observe(el));
    }

    // ============================================================
    // MAGNETIC BUTTON EFFECT
    // ============================================================
    const magneticElements = document.querySelectorAll('.magnetic');

    magneticElements.forEach(el => {
        el.addEventListener('mousemove', (e) => {
            const rect = el.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            const strength = 0.3;

            el.style.transform = `translate(${x * strength}px, ${y * strength}px)`;
        });

        el.addEventListener('mouseleave', () => {
            el.style.transform = 'translate(0, 0)';
        });
    });

    // ============================================================
    // SMOOTH CURSOR GLOW (Desktop only)
    // ============================================================
    if (window.matchMedia('(hover: hover)').matches) {
        const cursorGlow = document.createElement('div');
        cursorGlow.className = 'fixed pointer-events-none z-[9999] w-[300px] h-[300px] rounded-full opacity-0 transition-opacity duration-500';
        cursorGlow.style.cssText = `
            background: radial-gradient(circle, rgba(37, 99, 235, 0.04) 0%, transparent 70%);
            transform: translate(-50%, -50%);
        `;
        document.body.appendChild(cursorGlow);

        let glowVisible = false;

        document.addEventListener('mousemove', (e) => {
            cursorGlow.style.left = e.clientX + 'px';
            cursorGlow.style.top = e.clientY + 'px';

            if (!glowVisible) {
                glowVisible = true;
                cursorGlow.style.opacity = '1';
            }
        });

        document.addEventListener('mouseleave', () => {
            glowVisible = false;
            cursorGlow.style.opacity = '0';
        });
    }

    // ============================================================
    // TEXT SPLIT ANIMATION (Optional — add .split-text class)
    // ============================================================
    const splitTextElements = document.querySelectorAll('.split-text');

    splitTextElements.forEach(el => {
        const text = el.textContent;
        el.textContent = '';
        el.setAttribute('aria-label', text);

        [...text].forEach((char, i) => {
            const span = document.createElement('span');
            span.textContent = char === ' ' ? '\u00A0' : char;
            span.style.cssText = `
                display: inline-block;
                opacity: 0;
                transform: translateY(12px);
                transition: opacity 0.4s ease ${i * 0.02}s, transform 0.4s ease ${i * 0.02}s;
            `;
            el.appendChild(span);
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    el.querySelectorAll('span').forEach(span => {
                        span.style.opacity = '1';
                        span.style.transform = 'translateY(0)';
                    });
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        observer.observe(el);
    });

});

// ============================================================
// ANIMATE COUNTER FUNCTION
// ============================================================
function animateCounter(element, target, prefix = '', suffix = '') {
    const duration = 2000;
    const startTime = performance.now();
    const startValue = 0;

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Ease out cubic
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = Math.floor(startValue + (target - startValue) * eased);

        element.textContent = prefix + current.toLocaleString() + suffix;

        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            element.textContent = prefix + target.toLocaleString() + suffix;
        }
    }

    requestAnimationFrame(update);
}

// ============================================================
// TOAST NOTIFICATION
// ============================================================
window.showToast = function(message, type = 'success', duration = 5000) {
    const toast = document.createElement('div');
    const colors = {
        success: 'border-success/30 bg-success/10',
        error: 'border-danger/30 bg-danger/10',
        warning: 'border-warning/30 bg-warning/10',
        info: 'border-accent/30 bg-accent-muted',
    };
    const textColors = {
        success: 'text-success',
        error: 'text-danger',
        warning: 'text-warning',
        info: 'text-accent-light',
    };

    toast.className = `toast fixed top-6 right-6 z-[100] px-6 py-4 rounded-xl font-medium shadow-elevated border backdrop-blur-lg ${colors[type] || colors.info} ${textColors[type] || textColors.info}`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease-out forwards';
        setTimeout(() => toast.remove(), 300);
    }, duration);
};
