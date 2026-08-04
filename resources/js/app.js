import Alpine from 'alpinejs';
import Lenis from 'lenis';

window.Alpine = Alpine;
Alpine.start();

const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const isDesktop = window.matchMedia('(min-width: 768px) and (pointer: fine)').matches;

/* -------------------------------------------------------------------------
 * Scroll-reveal (unchanged): reveals `.reveal` as it enters the viewport.
 * ---------------------------------------------------------------------- */
function initReveal() {
    const targets = document.querySelectorAll('.reveal:not(.is-visible), .reveal-l:not(.is-visible), .reveal-r:not(.is-visible)');
    if (reduce || !('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-visible'));
        return;
    }
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                obs.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.12 });
    targets.forEach((el) => observer.observe(el));
}

/* -------------------------------------------------------------------------
 * Smooth momentum scrolling (Lenis) — desktop only, respects reduced-motion.
 * Phones keep native scrolling for a natural, gesture-friendly feel.
 * ---------------------------------------------------------------------- */
let lenis = null;
if (!reduce && isDesktop) {
    lenis = new Lenis({ duration: 1.1, smoothWheel: true, wheelMultiplier: 1, touchMultiplier: 1.5 });
    const raf = (t) => { lenis.raf(t); requestAnimationFrame(raf); };
    requestAnimationFrame(raf);

    // Smooth in-page anchor jumps (#reserve, #about, #menu, …)
    document.addEventListener('click', (e) => {
        const a = e.target.closest('a[href*="#"]');
        if (!a) return;
        let url;
        try { url = new URL(a.href, location.href); } catch { return; }
        if (url.pathname === location.pathname && url.hash && url.hash.length > 1) {
            const target = document.querySelector(url.hash);
            if (target) { e.preventDefault(); lenis.scrollTo(target, { offset: -10 }); }
        }
    });
}

/* -------------------------------------------------------------------------
 * 3D tilt: cards lean toward the cursor. Desktop only, reduced-motion safe.
 * ---------------------------------------------------------------------- */
if (!reduce && isDesktop) {
    document.querySelectorAll('[data-tilt]').forEach((card) => {
        const max = parseFloat(card.dataset.tilt) || 6;
        card.addEventListener('mousemove', (e) => {
            const r = card.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width - 0.5;
            const py = (e.clientY - r.top) / r.height - 0.5;
            card.style.transform = 'perspective(900px) rotateY(' + (px * max).toFixed(2) + 'deg) rotateX(' + (-py * max).toFixed(2) + 'deg)';
        });
        card.addEventListener('mouseleave', () => { card.style.transform = ''; });
    });
}

/* -------------------------------------------------------------------------
 * Wine-glass cursor — desktop only. A precise dot rides the true pointer;
 * the glass floats and trails behind it. Native cursor over form fields.
 * ---------------------------------------------------------------------- */
if (!reduce && isDesktop) {
    const dot = document.querySelector('[data-wc-dot]');
    const gl = document.querySelector('[data-wc-glass]');
    if (dot && gl) {
        const root = document.documentElement;
        root.classList.add('has-winecursor');
        let tx = window.innerWidth / 2, ty = window.innerHeight / 2, gx = tx, gy = ty, shown = false;

        window.addEventListener('mousemove', (e) => {
            tx = e.clientX; ty = e.clientY;
            dot.style.transform = 'translate3d(' + tx + 'px,' + ty + 'px,0)';
            if (!shown) { shown = true; root.classList.add('wc-on'); }
            const t = e.target;
            const interactive = t.closest && t.closest('a,button,[role="button"],label,summary,.cursor-pointer,[data-tilt]');
            const formish = t.closest && t.closest('input,textarea,select,[contenteditable="true"]');
            root.classList.toggle('wc-hover', !!interactive && !formish);
            root.classList.toggle('wc-hidden', !!formish);
        }, { passive: true });

        document.addEventListener('mouseleave', () => { root.classList.remove('wc-on'); shown = false; });

        const follow = () => {
            gx += (tx - gx) * 0.16;
            gy += (ty - gy) * 0.16;
            gl.style.transform = 'translate3d(' + gx.toFixed(1) + 'px,' + gy.toFixed(1) + 'px,0)';
            requestAnimationFrame(follow);
        };
        requestAnimationFrame(follow);
    }
}

/* -------------------------------------------------------------------------
 * Scroll-driven effects: cinematic hero zoom + the wine glass that fills as
 * you scroll from the hero down to the menu. One rAF-throttled handler.
 * ---------------------------------------------------------------------- */
const hero = document.querySelector('[data-hero]');
const heroMedia = document.querySelector('[data-hero-media]');
const heroCopy = document.querySelector('[data-hero-copy]');
const menuAnchor = document.querySelector('#menu');
const glass = document.querySelector('[data-wine-glass]');
const bar = document.querySelector('[data-wine-bar]');
const parallaxEls = document.querySelectorAll('[data-parallax]');

const clamp01 = (v) => Math.min(1, Math.max(0, v));

function setWine(fp) {
    if (glass) glass.style.setProperty('--fill', fp.toFixed(3));
    if (bar) bar.style.transform = 'scaleX(' + fp.toFixed(3) + ')';
}

function onScroll() {
    const y = window.scrollY || window.pageYOffset || 0;
    const vh = window.innerHeight;

    // Cinematic hero: media eases in scale, copy fades + lifts as it leaves.
    if (hero && heroMedia && !reduce) {
        const hh = hero.offsetHeight || vh;
        const p = clamp01(y / hh);
        heroMedia.style.transform = 'scale(' + (1 + p * 0.06).toFixed(4) + ')';
        if (heroCopy) {
            heroCopy.style.opacity = String(clamp01(1 - p * 1.15));
            heroCopy.style.transform = 'translateY(' + (p * -32).toFixed(1) + 'px)';
        }
    }

    // Wine fill: 0 at the top → full when the menu section is ~half in view.
    let target = vh * 2;
    if (menuAnchor) target = menuAnchor.getBoundingClientRect().top + y - vh * 0.5;
    setWine(clamp01(y / Math.max(1, target)));

    // Subtle parallax on tagged imagery (image sits 130% tall so edges stay covered).
    if (!reduce && parallaxEls.length) {
        parallaxEls.forEach((el) => {
            const speed = parseFloat(el.dataset.parallax) || 0.1;
            const r = el.getBoundingClientRect();
            if (r.bottom < -40 || r.top > vh + 40) return;
            const off = (r.top + r.height / 2 - vh / 2) / vh;
            el.style.transform = 'translate3d(0,' + (off * speed * -120).toFixed(1) + 'px,0)';
        });
    }
}

let ticking = false;
function scrollTick() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(() => { ticking = false; onScroll(); });
}

if (reduce) {
    setWine(0.28); // static partial fill, no motion
} else {
    if (lenis) lenis.on('scroll', scrollTick);
    else window.addEventListener('scroll', scrollTick, { passive: true });
    window.addEventListener('resize', scrollTick, { passive: true });
    onScroll();
}

/* -------------------------------------------------------------------------
 * Luxury loader: wine glass fills, then fades to reveal the hero.
 * Once per session, skippable, instant under reduced-motion / repeat visits.
 * ---------------------------------------------------------------------- */
function initLoader() {
    const loader = document.querySelector('[data-loader]');
    if (!loader) return;

    let done = false;
    const finish = () => {
        if (done) return;
        done = true;
        loader.classList.add('is-done');
        document.documentElement.classList.remove('is-loading');
        window.setTimeout(() => loader.remove(), 900);
    };

    if (reduce || sessionStorage.getItem('pp_seen')) {
        loader.remove();
        document.documentElement.classList.remove('is-loading');
        return;
    }
    sessionStorage.setItem('pp_seen', '1');
    document.documentElement.classList.add('is-loading');

    // Hand the loader off the instant the first hero video actually starts
    // playing, so it reveals moving video rather than a poster. Fallbacks: a
    // hard cap so it never hangs on a slow link, and any interaction skips it.
    const heroVideo = document.querySelector('[data-hero] video');
    if (heroVideo) {
        heroVideo.addEventListener('playing', () => window.setTimeout(finish, 150), { once: true });
    }
    window.setTimeout(finish, 2500);
    ['click', 'wheel', 'keydown', 'touchstart'].forEach((ev) =>
        window.addEventListener(ev, finish, { once: true, passive: true }));
}

initLoader();
document.addEventListener('DOMContentLoaded', initReveal);
document.addEventListener('livewire:navigated', initReveal);
document.addEventListener('livewire:update', () => requestAnimationFrame(initReveal));
