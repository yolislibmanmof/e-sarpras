document.addEventListener('DOMContentLoaded', function () {
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var bars = document.querySelectorAll('.bar-fill');
    if (reduceMotion || bars.length === 0 || !('IntersectionObserver' in window)) { return; }

    var obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
            if (en.isIntersecting) {
                var bar = en.target;
                var target = bar.getAttribute('data-width') || bar.style.width || '0%';
                bar.style.width = '0%';
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () { bar.style.width = target; });
                });
                obs.unobserve(bar);
            }
        });
    }, { threshold: 0.4 });
    bars.forEach(function (b) { obs.observe(b); });
});