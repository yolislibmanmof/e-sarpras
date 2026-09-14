(function () {
    'use strict';

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.addEventListener('DOMContentLoaded', function () {
        initRotWord();
        initParallax();
        initSpotlight();
        initScramble();
        initVt();
        initTesti();
        initFaq();
    });

    /* Kata berputar 3D pada judul hero */
    function initRotWord() {
        var el = document.getElementById('rotWord');
        if (!el || reduceMotion) { return; }
        var words = ['Transparan', 'Akuntabel', 'Modern', 'Terintegrasi'];
        var i = 0;
        setInterval(function () {
            el.classList.add('out');
            setTimeout(function () {
                i = (i + 1) % words.length;
                el.textContent = words[i];
                el.classList.remove('out');
                el.classList.add('in-below');
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () { el.classList.remove('in-below'); });
                });
            }, 460);
        }, 2600);
    }

    /* Parallax aurora & panel kaca mengikuti mouse */
    function initParallax() {
        var hero = document.getElementById('hero');
        if (!hero || reduceMotion) { return; }
        var layers = hero.querySelectorAll('[data-depth]');
        hero.addEventListener('mousemove', function (e) {
            var r = hero.getBoundingClientRect();
            var x = (e.clientX - r.left) / r.width - 0.5;
            var y = (e.clientY - r.top) / r.height - 0.5;
            layers.forEach(function (l) {
                var d = parseFloat(l.getAttribute('data-depth'));
                l.style.transform = 'translate(' + (x * d).toFixed(1) + 'px,' + (y * d).toFixed(1) + 'px)';
            });
        });
        hero.addEventListener('mouseleave', function () {
            layers.forEach(function (l) { l.style.transform = ''; });
        });
    }

    /* Spotlight pada kartu bento */
    function initSpotlight() {
        document.querySelectorAll('.bento').forEach(function (card) {
            card.addEventListener('mousemove', function (e) {
                var r = card.getBoundingClientRect();
                card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
                card.style.setProperty('--my', (e.clientY - r.top) + 'px');
            });
        });
    }

    /* Judul seksi scramble-decode */
    function initScramble() {
        var els = document.querySelectorAll('[data-scramble]');
        if (els.length === 0 || !('IntersectionObserver' in window) || reduceMotion) { return; }
        var chars = '!<>-_\\/[]{}=+*^?#';

        var run = function (el) {
            var original = el.getAttribute('data-text') || el.textContent;
            el.setAttribute('data-text', original);
            var frame = 0, total = 22;
            var timer = setInterval(function () {
                frame++;
                var out = '';
                for (var i = 0; i < original.length; i++) {
                    out += (i < (frame / total) * original.length) ? original[i] : chars[(Math.random() * chars.length) | 0];
                }
                el.textContent = out;
                if (frame >= total) { el.textContent = original; clearInterval(timer); }
            }, 34);
        };

        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { run(en.target); obs.unobserve(en.target); }
            });
        }, { threshold: 0.6 });
        els.forEach(function (el) { obs.observe(el); });
    }

    /* Progres garis timeline vertikal */
    function initVt() {
        var wrap = document.querySelector('.vtimeline');
        var bar = document.getElementById('vtProgress');
        if (!wrap || !bar) { return; }
        var items = wrap.querySelectorAll('.vt-item');

        var onScroll = function () {
            var r = wrap.getBoundingClientRect();
            var vh = window.innerHeight;
            var progress = Math.min(Math.max((vh * 0.72 - r.top) / r.height, 0), 1);
            bar.style.height = (progress * 100) + '%';
            items.forEach(function (item) {
                item.classList.toggle('active', item.getBoundingClientRect().top < vh * 0.72);
            });
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    /* Karusel testimoni otomatis */
    function initTesti() {
        var track = document.getElementById('testiTrack');
        var dotsWrap = document.getElementById('testiDots');
        if (!track || !dotsWrap) { return; }
        var slides = track.children.length;
        var idx = 0;

        for (var i = 0; i < slides; i++) {
            var b = document.createElement('button');
            b.setAttribute('aria-label', 'Testimoni ' + (i + 1));
            (function (n) { b.addEventListener('click', function () { go(n); }); })(i);
            dotsWrap.appendChild(b);
        }

        function go(n) {
            idx = n;
            track.style.transform = 'translateX(-' + (idx * 100) + '%)';
            for (var i = 0; i < dotsWrap.children.length; i++) {
                dotsWrap.children[i].classList.toggle('active', i === idx);
            }
        }
        go(0);
        if (!reduceMotion) {
            setInterval(function () { go((idx + 1) % slides); }, 5200);
        }
    }

    /* FAQ akordeon */
    function initFaq() {
        document.querySelectorAll('.faq-item').forEach(function (item) {
            var q = item.querySelector('.faq-q');
            var a = item.querySelector('.faq-a');
            q.addEventListener('click', function () {
                var open = item.classList.toggle('open');
                a.style.maxHeight = open ? a.scrollHeight + 'px' : '0px';
            });
        });
    }
})();