(function () {
    'use strict';

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.addEventListener('DOMContentLoaded', function () {
        initWords();
        initTyped();
        initHScroll();
    });

    /* ============================================================ */
    /* WORD-MASK REVEAL                                              */
    /* Berlaku untuk [data-words] DI BERANDA serta seluruh           */
    /* kalimat kecil pada sub-hero halaman public lainnya.           */
    /* ============================================================ */
    function initWords() {
        var els = document.querySelectorAll('[data-words], .sub-hero p');
        if (els.length === 0 || !('IntersectionObserver' in window) || reduceMotion) { return; }

        els.forEach(function (el) {
            var text = el.textContent.trim();
            if (text === '' || el.classList.contains('words-done')) { return; }
            el.classList.add('words-done');
            el.textContent = '';
            text.split(/\s+/).forEach(function (word, i) {
                var w = document.createElement('span');
                w.className = 'w';
                var inner = document.createElement('i');
                inner.textContent = word;
                inner.style.transitionDelay = (i * 45) + 'ms';
                w.appendChild(inner);
                el.appendChild(w);
                el.appendChild(document.createTextNode(' '));
            });
        });

        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('words-in'); obs.unobserve(en.target); }
            });
        }, { threshold: 0.4 });
        els.forEach(function (el) { obs.observe(el); });
    }

    /* ============================================================ */
    /* EFEK MENGETIK pada hero beranda                               */
    /* ============================================================ */
    function initTyped() {
        var el = document.getElementById('typed');
        if (!el || reduceMotion) { return; }
        var phrases = [
            'Satu pintu untuk semua layanan fasilitas kampus.',
            'Laporan ditangani terukur dan terlacak.',
            'Data sarpras siap untuk akreditasi.'
        ];
        var pi = 0, ci = 0, deleting = false;
        (function tick() {
            var current = phrases[pi];
            el.textContent = current.slice(0, ci);
            if (!deleting) {
                ci++;
                if (ci > current.length) { deleting = true; setTimeout(tick, 1700); return; }
            } else {
                ci--;
                if (ci === 0) { deleting = false; pi = (pi + 1) % phrases.length; }
            }
            setTimeout(tick, deleting ? 26 : 55);
        })();
    }

    /* ============================================================ */
    /* GALERI FASILITAS: konveyor otomatis                           */
    /* ============================================================ */
    function initHScroll() {
        var track = document.getElementById('hscrollTrack');
        if (!track) { return; }
        track.innerHTML += track.innerHTML;
    }
})();