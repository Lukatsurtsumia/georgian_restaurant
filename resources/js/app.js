import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/*
 * Scroll-reveal: reveals `.reveal` elements as they enter the viewport.
 * Honours prefers-reduced-motion (CSS shows them instantly there anyway),
 * and re-scans after Livewire DOM swaps so newly rendered sections animate too.
 */
function initReveal() {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const targets = document.querySelectorAll('.reveal:not(.is-visible)');

    if (prefersReduced || !('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -10% 0px', threshold: 0.12 }
    );

    targets.forEach((el) => observer.observe(el));
}

document.addEventListener('DOMContentLoaded', initReveal);
document.addEventListener('livewire:navigated', initReveal);
document.addEventListener('livewire:update', () => requestAnimationFrame(initReveal));
