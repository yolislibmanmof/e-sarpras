(function () {
    'use strict';
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.addEventListener('DOMContentLoaded', function () {
        initLoader();
        initSidebarMobile();
        initSidebarCollapse();
        initTheme();
        initClock();
        initFullscreen();
        initRipple();
        initReveal();
        initCounters();
        initToasts();
        initFab();
        initPalette();
    });

    function initLoader() {
        var loader = document.getElementById('admin-loader');
        if (!loader) { return; }
        setTimeout(function () { loader.classList.add('off'); setTimeout(function () { loader.remove(); }, 600); }, 450);
    }

    function initSidebarMobile() {
        var toggle = document.getElementById('sidebarToggle');
        var sidebar = document.getElementById('sidebar');
        if (toggle && sidebar) {
            toggle.addEventListener('click', function () { sidebar.classList.toggle('open'); });
        }
    }

    function initSidebarCollapse() {
        var btn = document.getElementById('sidebarCollapse');
        if (!btn) { return; }
        try {
            if (localStorage.getItem('es_admin_mini') === '1' && window.innerWidth > 900) { document.body.classList.add('mini'); }
        } catch (e) {}
        btn.addEventListener('click', function () {
            document.body.classList.toggle('mini');
            try { localStorage.setItem('es_admin_mini', document.body.classList.contains('mini') ? '1' : '0'); } catch (e) {}
        });
    }

    function initTheme() {
        var btn = document.getElementById('themeToggle');
        if (!btn) { return; }
        btn.addEventListener('click', function () {
            var root = document.documentElement;
            var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            if (next === 'dark') { root.setAttribute('data-theme', 'dark'); } else { root.removeAttribute('data-theme'); }
            try { localStorage.setItem('es_theme', next); } catch (e) {}
        });
    }

    function initClock() {
        var el = document.getElementById('topbarClock');
        if (!el) { return; }
        var tick = function () {
            var d = new Date();
            var p = function (n) { return (n < 10 ? '0' : '') + n; };
            el.textContent = p(d.getHours()) + ':' + p(d.getMinutes()) + ':' + p(d.getSeconds());
        };
        tick();
        setInterval(tick, 1000);
    }

    function initFullscreen() {
        var btn = document.getElementById('fullscreenToggle');
        if (!btn) { return; }
        btn.addEventListener('click', function () {
            if (document.fullscreenElement) { document.exitFullscreen(); }
            else { document.documentElement.requestFullscreen(); }
        });
    }

    function initRipple() {
        if (reduceMotion) { return; }
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn, .fab-main');
            if (!btn) { return; }
            var r = btn.getBoundingClientRect();
            var span = document.createElement('span');
            span.className = 'ripple';
            span.style.left = (e.clientX - r.left) + 'px';
            span.style.top = (e.clientY - r.top) + 'px';
            btn.appendChild(span);
            setTimeout(function () { span.remove(); }, 700);
        });
    }

    function initReveal() {
        var targets = document.querySelectorAll('.card, .stat-card, .table-card, .page-head, .welcome, .form-card');
        if (!('IntersectionObserver' in window) || reduceMotion) { return; }
        targets.forEach(function (el, i) {
            el.classList.add('reveal');
            el.style.transitionDelay = ((i % 4) * 80) + 'ms';
        });
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('revealed'); obs.unobserve(en.target); }
            });
        }, { threshold: 0.1 });
        targets.forEach(function (el) { obs.observe(el); });
    }

    function initCounters() {
        var values = document.querySelectorAll('.stat-value');
        if (values.length === 0 || !('IntersectionObserver' in window) || reduceMotion) { return; }
        var animate = function (el) {
            var target = parseInt(el.textContent.replace(/[^\d]/g, ''), 10) || 0;
            var duration = 1100, startTs = null;
            var step = function (ts) {
                if (!startTs) { startTs = ts; }
                var p = Math.min((ts - startTs) / duration, 1);
                el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
                if (p < 1) { window.requestAnimationFrame(step); }
            };
            window.requestAnimationFrame(step);
        };
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { animate(en.target); obs.unobserve(en.target); }
            });
        }, { threshold: 0.4 });
        values.forEach(function (el) { obs.observe(el); });
    }

    /* Flash -> Toast */
    function initToasts() {
        var flashes = document.querySelectorAll('.flash');
        if (flashes.length === 0) { return; }
        var wrap = document.createElement('div');
        wrap.id = 'toastWrap';
        document.body.appendChild(wrap);
        flashes.forEach(function (f) {
            var t = document.createElement('div');
            t.className = 'toast ' + (f.classList.contains('flash-error') ? 'toast-error' : 'toast-success');
            t.textContent = f.textContent.trim();
            wrap.appendChild(t);
            f.remove();
            setTimeout(function () {
                t.style.transition = 'opacity .5s ease, transform .5s ease';
                t.style.opacity = '0';
                t.style.transform = 'translateX(30px)';
                setTimeout(function () { t.remove(); }, 500);
            }, 4200);
        });
    }

    function initFab() {
        var fab = document.getElementById('fab');
        var main = document.getElementById('fabMain');
        if (!fab || !main) { return; }
        main.addEventListener('click', function (e) {
            e.stopPropagation();
            fab.classList.toggle('open');
        });
        document.addEventListener('click', function (e) {
            if (!fab.contains(e.target)) { fab.classList.remove('open'); }
        });
    }

    function initPalette() {
        var palette = document.getElementById('palette');
        var input = document.getElementById('paletteInput');
        var list = document.getElementById('paletteList');
        var openBtn = document.getElementById('paletteOpen');
        if (!palette || !input || !list) { return; }
        var items = list.querySelectorAll('a');
        function open() { palette.classList.add('on'); input.value = ''; filter(''); setTimeout(function () { input.focus(); }, 60); }
        function close() { palette.classList.remove('on'); }
        function filter(q) {
            q = q.toLowerCase();
            items.forEach(function (a) { a.style.display = a.textContent.toLowerCase().indexOf(q) !== -1 ? '' : 'none'; });
        }
        if (openBtn) { openBtn.addEventListener('click', open); }
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); open(); }
            if (e.key === 'Escape') { close(); }
        });
        palette.addEventListener('click', function (e) { if (e.target === palette) { close(); } });
        input.addEventListener('input', function () { filter(input.value); });
    }
})();