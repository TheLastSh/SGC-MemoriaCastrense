import './bootstrap';

// ─── Scroll Reveal ─────────────────────────────────────────────
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('revealed');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-reveal]').forEach(el => observer.observe(el));
});

// ─── Navbar Scroll Effect ─────────────────────────────────────
let ticking = false;
const navbar = document.querySelector('nav.sticky');

if (navbar) {
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                navbar.classList.toggle('scrolled', window.scrollY > 40);
                ticking = false;
            });
            ticking = true;
        }
    });
}

// ─── Stat Counter ──────────────────────────────────────────────
document.querySelectorAll('[data-counter]').forEach(el => {
    const target = parseInt(el.dataset.counter, 10);
    const duration = 1200;
    const startTime = performance.now();

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const animate = (now) => {
                    const elapsed = now - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.round(eased * target);
                    if (progress < 1) requestAnimationFrame(animate);
                    else el.textContent = target;
                };
                requestAnimationFrame(animate);
                counterObserver.unobserve(el);
            }
        });
    }, { threshold: 0.5 });

    counterObserver.observe(el);
});
