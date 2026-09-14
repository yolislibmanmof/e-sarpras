(function () {
    'use strict';

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var finePointer = window.matchMedia && window.matchMedia('(pointer: fine)').matches;

    document.addEventListener('DOMContentLoaded', function () {
        initNavbar();
        initMobileMenu();
        initScrollProgress();
        initBackToTop();
        initReveal();
        initCounters();
        initSmoothAnchors();
        initHeroEffects();
        initFloatingWords();
        initMarquee();
        initTilt();
        initMagnetic();
        initRipple();
        initCursor();
        /* PERUBAHAN: intro kembang api hanya pada laman utama */
        var isHome = !!document.getElementById('hero');
        if (!reduceMotion && isHome) { initIntro(); }
    });

    /* ============================================================ */
    /* INTRO: garis bertabrakan -> kembang api -> E-SARPRAS          */
    /* ============================================================ */
    function initIntro() {
        var overlay = document.createElement('div');
        overlay.id = 'intro-overlay';
        overlay.innerHTML =
            '<canvas id="intro-canvas"></canvas>' +
            '<div class="intro-center">' +
            '<div class="intro-title" id="introTitle"></div>' +
            '<div class="intro-tag" id="introTag">Sistem Informasi Sarana &amp; Prasarana Kampus</div>' +
            '</div>' +
            '<button type="button" class="intro-skip">Lewati &#8594;</button>';
        document.body.appendChild(overlay);
        document.body.classList.add('intro-lock');

        var text = 'E-SARPRAS';
        var title = overlay.querySelector('#introTitle');
        for (var i = 0; i < text.length; i++) {
            var s = document.createElement('span');
            s.className = 'intro-letter';
            s.textContent = text[i];
            s.style.transitionDelay = (i * 70) + 'ms';
            title.appendChild(s);
        }

        var canvas = overlay.querySelector('#intro-canvas');
        var ctx = canvas.getContext('2d');
        var W, H, dpr;

        function resize() {
            dpr = Math.min(window.devicePixelRatio || 1, 2);
            W = overlay.clientWidth; H = overlay.clientHeight;
            canvas.width = W * dpr; canvas.height = H * dpr;
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        }
        resize();
        window.addEventListener('resize', resize);

        var particles = [], rings = [];
        var exploded = false, start = null, raf;
        var LINE_MS = 700;
        var colors = ['#2dd4bf', '#f0b429', '#a7f3d0', '#ffffff', '#14957b'];

        function drawLine(x1, y1, x2, y2) {
            var grad = ctx.createLinearGradient(x1, y1, x2, y2);
            grad.addColorStop(0, 'rgba(45,212,191,0)');
            grad.addColorStop(1, 'rgba(45,212,191,0.95)');
            ctx.strokeStyle = grad;
            ctx.lineWidth = 2.5;
            ctx.shadowBlur = 14;
            ctx.shadowColor = '#2dd4bf';
            ctx.beginPath(); ctx.moveTo(x1, y1); ctx.lineTo(x2, y2); ctx.stroke();
            ctx.shadowBlur = 0;
        }

        function glowDot(x, y) {
            ctx.fillStyle = '#ffffff';
            ctx.shadowBlur = 18;
            ctx.shadowColor = '#2dd4bf';
            ctx.beginPath(); ctx.arc(x, y, 3, 0, Math.PI * 2); ctx.fill();
            ctx.shadowBlur = 0;
        }

        function explode(cx, cy) {
            exploded = true;
            for (var i = 0; i < 170; i++) {
                var angle = Math.random() * Math.PI * 2;
                var speed = 2 + Math.random() * 7.5;
                particles.push({
                    x: cx, y: cy,
                    vx: Math.cos(angle) * speed,
                    vy: Math.sin(angle) * speed - 1.6,
                    life: 1,
                    decay: 0.011 + Math.random() * 0.02,
                    size: 1 + Math.random() * 2.6,
                    color: colors[(Math.random() * colors.length) | 0]
                });
            }
            rings.push({ r: 4, alpha: 0.9 });
            rings.push({ r: 1, alpha: 0.55 });

            setTimeout(function () {
                title.querySelectorAll('.intro-letter').forEach(function (l) { l.classList.add('in'); });
            }, 120);
            setTimeout(function () {
                overlay.querySelector('#introTag').classList.add('in');
            }, 950);
        }

        function frame(ts) {
            if (!start) { start = ts; }
            var t = ts - start;
            ctx.clearRect(0, 0, W, H);
            var cx = W / 2, cy = H / 2;

            if (!exploded) {
                var p = Math.min(t / LINE_MS, 1);
                var e = p * p * p;
                var lx = cx * e;
                drawLine(0, cy, lx, cy);
                drawLine(W, cy, W - lx, cy);
                glowDot(lx, cy);
                glowDot(W - lx, cy);
                if (p >= 1) { explode(cx, cy); }
            }

            for (var r = rings.length - 1; r >= 0; r--) {
                var ring = rings[r];
                ring.r += 6.5; ring.alpha -= 0.02;
                if (ring.alpha <= 0) { rings.splice(r, 1); continue; }
                ctx.beginPath();
                ctx.arc(cx, cy, ring.r, 0, Math.PI * 2);
                ctx.strokeStyle = 'rgba(45,212,191,' + ring.alpha.toFixed(3) + ')';
                ctx.lineWidth = 2;
                ctx.stroke();
            }

            for (var i = particles.length - 1; i >= 0; i--) {
                var pt = particles[i];
                pt.x += pt.vx; pt.y += pt.vy;
                pt.vy += 0.06; pt.vx *= 0.985; pt.vy *= 0.985;
                pt.life -= pt.decay;
                if (pt.life <= 0) { particles.splice(i, 1); continue; }
                ctx.globalAlpha = Math.max(pt.life, 0);
                ctx.fillStyle = pt.color;
                ctx.shadowBlur = 8;
                ctx.shadowColor = pt.color;
                ctx.beginPath(); ctx.arc(pt.x, pt.y, pt.size, 0, Math.PI * 2); ctx.fill();
                ctx.shadowBlur = 0;
                ctx.globalAlpha = 1;
            }

            if (overlay.isConnected) { raf = requestAnimationFrame(frame); }
        }
        raf = requestAnimationFrame(frame);

        var finished = false;
        function finish() {
            if (finished) { return; }
            finished = true;
            overlay.classList.add('done');
            document.body.classList.remove('intro-lock');
            setTimeout(function () {
                cancelAnimationFrame(raf);
                if (overlay.isConnected) { overlay.remove(); }
            }, 750);
        }

        setTimeout(finish, 2600);
        overlay.addEventListener('click', function () {
            cancelAnimationFrame(raf);
            finish();
        });
    }

    /* ============================================================ */
    /* HERO: konstelasi partikel + judul shimmer                     */
    /* ============================================================ */
    function initHeroEffects() {
        var hero = document.querySelector('.hero');
        if (!hero) { return; }
        var h1 = hero.querySelector('h1');
        if (h1) { h1.classList.add('shimmer-title'); }
        if (reduceMotion) { return; }

        var canvas = document.createElement('canvas');
        canvas.className = 'hero-canvas';
        hero.appendChild(canvas);
        var ctx = canvas.getContext('2d');
        var pts = [], W, H, running = false, raf;

        function resize() {
            W = hero.clientWidth; H = hero.clientHeight;
            canvas.width = W; canvas.height = H;
            var count = Math.min(70, Math.floor((W * H) / 16000));
            pts = [];
            for (var i = 0; i < count; i++) {
                pts.push({
                    x: Math.random() * W, y: Math.random() * H,
                    vx: (Math.random() - 0.5) * 0.45,
                    vy: (Math.random() - 0.5) * 0.45
                });
            }
        }
        resize();
        window.addEventListener('resize', resize);

        var mouse = { x: -9999, y: -9999 };
        hero.addEventListener('mousemove', function (e) {
            var rect = hero.getBoundingClientRect();
            mouse.x = e.clientX - rect.left;
            mouse.y = e.clientY - rect.top;
        });
        hero.addEventListener('mouseleave', function () { mouse.x = -9999; mouse.y = -9999; });

        function loop() {
            ctx.clearRect(0, 0, W, H);
            var i, a, b;
            for (i = 0; i < pts.length; i++) {
                var p = pts[i];
                p.x += p.vx; p.y += p.vy;
                if (p.x < 0 || p.x > W) { p.vx *= -1; }
                if (p.y < 0 || p.y > H) { p.vy *= -1; }
                ctx.fillStyle = 'rgba(45,212,191,0.55)';
                ctx.beginPath(); ctx.arc(p.x, p.y, 1.6, 0, Math.PI * 2); ctx.fill();
            }
            for (a = 0; a < pts.length; a++) {
                for (b = a + 1; b < pts.length; b++) {
                    var dx = pts[a].x - pts[b].x, dy = pts[a].y - pts[b].y;
                    var d2 = dx * dx + dy * dy;
                    if (d2 < 12100) {
                        var alpha = (1 - Math.sqrt(d2) / 110) * 0.28;
                        ctx.strokeStyle = 'rgba(167,243,208,' + alpha.toFixed(3) + ')';
                        ctx.lineWidth = 1;
                        ctx.beginPath(); ctx.moveTo(pts[a].x, pts[a].y); ctx.lineTo(pts[b].x, pts[b].y); ctx.stroke();
                    }
                }
                var mdx = pts[a].x - mouse.x, mdy = pts[a].y - mouse.y;
                var md2 = mdx * mdx + mdy * mdy;
                if (md2 < 22500) {
                    var malpha = (1 - Math.sqrt(md2) / 150) * 0.4;
                    ctx.strokeStyle = 'rgba(240,180,41,' + malpha.toFixed(3) + ')';
                    ctx.beginPath(); ctx.moveTo(pts[a].x, pts[a].y); ctx.lineTo(mouse.x, mouse.y); ctx.stroke();
                }
            }
            if (running) { raf = requestAnimationFrame(loop); }
        }

        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) {
                var visible = entries[0].isIntersecting;
                if (visible && !running) { running = true; raf = requestAnimationFrame(loop); }
                if (!visible && running) { running = false; cancelAnimationFrame(raf); }
            }, { threshold: 0.05 }).observe(hero);
        } else {
            running = true;
            loop();
        }
    }

    /* ============================================================ */
    /* KATA-KATA MELAYANG di hero                                    */
    /* ============================================================ */
    function initFloatingWords() {
        var hero = document.querySelector('.hero');
        if (!hero || reduceMotion) { return; }
        var words = ['ASET', 'K3L', 'APAR', 'AKREDITASI', 'PEMELIHARAAN', 'TIKET', 'RUANGAN', 'GEDUNG', 'SURVEI', 'DISPOSISI', 'UTILITAS', 'INVENTARIS', 'SARPRAS', 'BAST', 'STOK'];
        var wrap = document.createElement('div');
        wrap.className = 'float-words';
        for (var i = 0; i < 15; i++) {
            var w = document.createElement('span');
            w.className = 'float-word';
            w.textContent = words[i % words.length];
            w.style.left = (Math.random() * 90) + '%';
            w.style.top = (10 + Math.random() * 80) + '%';
            w.style.fontSize = (0.62 + Math.random() * 0.7).toFixed(2) + 'rem';
            w.style.animationDuration = (14 + Math.random() * 18).toFixed(1) + 's';
            w.style.animationDelay = '-' + (Math.random() * 20).toFixed(1) + 's';
            w.style.setProperty('--dx', ((Math.random() - 0.5) * 180).toFixed(0) + 'px');
            wrap.appendChild(w);
        }
        hero.appendChild(wrap);
    }

    /* ============================================================ */
    /* MARQUEE di dasar hero                                         */
    /* ============================================================ */
    function initMarquee() {
        var hero = document.querySelector('.hero');
        if (!hero) { return; }
        var items = ['Inventarisasi Aset', 'Tiket Kerusakan', 'Peminjaman', 'Pemeliharaan Berkala', 'K3L', 'Survei Kepuasan', 'Laporan Akreditasi', 'Disposisi Surat'];
        var half = '';
        for (var r = 0; r < 2; r++) {
            items.forEach(function (t) {
                half += '<span class="marquee-item">' + t + '</span><span class="marquee-dot">&#10022;</span>';
            });
        }
        var wrap = document.createElement('div');
        wrap.className = 'hero-marquee';
        wrap.innerHTML = '<div class="marquee-track">' + half + '</div>';
        hero.appendChild(wrap);
    }

    /* ============================================================ */
    /* TILT 3D, MAGNETIK, RIPPLE, KURSOR                             */
    /* ============================================================ */
    function initTilt() {
        if (!finePointer || reduceMotion) { return; }
        document.querySelectorAll('.card, .stat-card').forEach(function (card) {
            card.addEventListener('mousemove', function (e) {
                if (card.classList.contains('reveal') && !card.classList.contains('revealed')) { return; }
                var rect = card.getBoundingClientRect();
                var px = (e.clientX - rect.left) / rect.width - 0.5;
                var py = (e.clientY - rect.top) / rect.height - 0.5;
                card.style.transform = 'perspective(800px) rotateY(' + (px * 7).toFixed(2) + 'deg) rotateX(' + (-py * 7).toFixed(2) + 'deg) translateY(-6px)';
            });
            card.addEventListener('mouseleave', function () { card.style.transform = ''; });
        });
    }

    function initMagnetic() {
        if (!finePointer || reduceMotion) { return; }
        document.querySelectorAll('.hero .btn, .tracking-form .btn').forEach(function (btn) {
            btn.addEventListener('mousemove', function (e) {
                var rect = btn.getBoundingClientRect();
                var dx = e.clientX - (rect.left + rect.width / 2);
                var dy = e.clientY - (rect.top + rect.height / 2);
                btn.style.transform = 'translate(' + (dx * 0.18).toFixed(1) + 'px,' + (dy * 0.22).toFixed(1) + 'px)';
            });
            btn.addEventListener('mouseleave', function () { btn.style.transform = ''; });
        });
    }

    function initRipple() {
        if (reduceMotion) { return; }
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn');
            if (!btn) { return; }
            var rect = btn.getBoundingClientRect();
            var span = document.createElement('span');
            span.className = 'ripple';
            span.style.left = (e.clientX - rect.left) + 'px';
            span.style.top = (e.clientY - rect.top) + 'px';
            btn.appendChild(span);
            setTimeout(function () { span.remove(); }, 700);
        });
    }

    function initCursor() {
        if (!finePointer || reduceMotion) { return; }
        var glow = document.createElement('div');
        glow.id = 'cursor-glow';
        var dot = document.createElement('div');
        dot.id = 'cursor-dot';
        document.body.appendChild(glow);
        document.body.appendChild(dot);

        var x = window.innerWidth / 2, y = window.innerHeight / 2, gx = x, gy = y;
        document.addEventListener('mousemove', function (e) {
            x = e.clientX; y = e.clientY;
            dot.style.transform = 'translate(' + x + 'px,' + y + 'px)';
        });
        (function loop() {
            gx += (x - gx) * 0.09;
            gy += (y - gy) * 0.09;
            glow.style.transform = 'translate(' + gx + 'px,' + gy + 'px)';
            requestAnimationFrame(loop);
        })();
    }

    /* ============================================================ */
    /* FITUR DASAR                                                   */
    /* ============================================================ */
    function initNavbar() {
        var navbar = document.querySelector('.navbar');
        if (!navbar) { return; }
        var onScroll = function () { navbar.classList.toggle('scrolled', window.scrollY > 12); };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    function initMobileMenu() {
        var toggle = document.getElementById('navbarToggle');
        var menu = document.getElementById('navbarMenu');
        if (toggle && menu) {
            toggle.addEventListener('click', function () {
                menu.classList.toggle('open');
                toggle.classList.toggle('active');
            });
        }
    }

    function initScrollProgress() {
        var bar = document.createElement('div');
        bar.id = 'scroll-progress';
        document.body.appendChild(bar);
        var onScroll = function () {
            var max = document.documentElement.scrollHeight - window.innerHeight;
            bar.style.width = (max > 0 ? (window.scrollY / max) * 100 : 0) + '%';
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    function initBackToTop() {
        var btn = document.createElement('button');
        btn.className = 'back-to-top';
        btn.setAttribute('aria-label', 'Kembali ke atas');
        btn.innerHTML = '&#8593;';
        document.body.appendChild(btn);
        var onScroll = function () { btn.classList.toggle('show', window.scrollY > 420); };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        btn.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
    }

    function initReveal() {
        var targets = document.querySelectorAll('.card, .stat-card, .section-title, .page-head-public, .public-form-card, .hero-text, .tracking-form');
        if (!('IntersectionObserver' in window)) { return; }
        targets.forEach(function (el, i) {
            el.classList.add('reveal');
            el.style.transitionDelay = ((i % 4) * 90) + 'ms';
        });
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        targets.forEach(function (el) { observer.observe(el); });
    }

    function initCounters() {
        var values = document.querySelectorAll('.stat-value');
        if (values.length === 0 || !('IntersectionObserver' in window)) { return; }
        var animate = function (el) {
            var target = parseInt(el.textContent.replace(/[^\d]/g, ''), 10) || 0;
            var duration = 1300, startTs = null;
            var step = function (ts) {
                if (!startTs) { startTs = ts; }
                var progress = Math.min((ts - startTs) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(target * eased);
                if (progress < 1) { window.requestAnimationFrame(step); }
            };
            window.requestAnimationFrame(step);
        };
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) { animate(entry.target); observer.unobserve(entry.target); }
            });
        }, { threshold: 0.4 });
        values.forEach(function (el) { observer.observe(el); });
    }

    function initSmoothAnchors() {
        document.querySelectorAll('a[href^="#"]').forEach(function (link) {
            link.addEventListener('click', function (event) {
                var target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    event.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }
})();