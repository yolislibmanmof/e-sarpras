<div class="auth-brand">
    <div class="auth-orb o1"></div>
    <div class="auth-orb o2"></div>
    <canvas id="authCanvas" class="auth-canvas"></canvas>

    <span class="auth-tag">Welcome to Admin e-Sarpras Dashboard Login</span>
    <h1><?= e(config('app.name')); ?></h1>
    <p>Sistem Informasi Manajemen Aset dan Operasional Sarana &amp; Prasarana. Transparan, akuntabel, dan siap akreditasi.</p>
    <a class="btn btn-light auth-back" href="<?= base_url('/'); ?>">&larr; Kembali ke Laman Publik</a>
</div>

<div class="auth-side">
    <div class="auth-card">
        <div class="auth-logo">eS</div>
        <h2><?= e(config('app.name')); ?></h2>
        <p class="auth-subtitle">Panel Administrasi Sarana &amp; Prasarana</p>

        <?php $error = \App\Core\Session::getFlash('error'); ?>
        <?php if ($error !== null): ?>
            <div class="auth-alert"><?= e($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= admin_url('/login'); ?>" class="auth-form" autocomplete="off">
            <?= csrf_field(); ?>
            <label for="username">Username atau Email</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
        </form>

        <p class="auth-note">Akses terbatas untuk petugas Sarpras dan pimpinan.</p>
    </div>
</div>

<script>
(function () {
    var c = document.getElementById('authCanvas');
    if (!c) { return; }
    var x = c.getContext('2d'), W, H, pts = [], run = true;

    function rs() {
        var b = c.parentElement.getBoundingClientRect();
        W = c.width = b.width; H = c.height = b.height;
        var n = Math.min(60, Math.floor(W * H / 18000));
        pts = [];
        for (var i = 0; i < n; i++) {
            pts.push({ x: Math.random() * W, y: Math.random() * H, vx: (Math.random() - 0.5) * 0.4, vy: (Math.random() - 0.5) * 0.4 });
        }
    }
    rs();
    window.addEventListener('resize', rs);

    var m = { x: -9999, y: -9999 };
    c.parentElement.addEventListener('mousemove', function (e) {
        var r = c.getBoundingClientRect();
        m.x = e.clientX - r.left; m.y = e.clientY - r.top;
    });
    c.parentElement.addEventListener('mouseleave', function () { m.x = -9999; m.y = -9999; });

    function loop() {
        x.clearRect(0, 0, W, H);
        var i, a, b;
        for (i = 0; i < pts.length; i++) {
            var p = pts[i];
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0 || p.x > W) { p.vx *= -1; }
            if (p.y < 0 || p.y > H) { p.vy *= -1; }
            x.fillStyle = 'rgba(45,212,191,.5)';
            x.beginPath(); x.arc(p.x, p.y, 1.5, 0, 6.283); x.fill();
        }
        for (a = 0; a < pts.length; a++) {
            for (b = a + 1; b < pts.length; b++) {
                var dx = pts[a].x - pts[b].x, dy = pts[a].y - pts[b].y, d = dx * dx + dy * dy;
                if (d < 12100) {
                    x.strokeStyle = 'rgba(167,243,208,' + ((1 - Math.sqrt(d) / 110) * 0.25).toFixed(3) + ')';
                    x.beginPath(); x.moveTo(pts[a].x, pts[a].y); x.lineTo(pts[b].x, pts[b].y); x.stroke();
                }
            }
            var mx = pts[a].x - m.x, my = pts[a].y - m.y, md = mx * mx + my * my;
            if (md < 22500) {
                x.strokeStyle = 'rgba(240,180,41,' + ((1 - Math.sqrt(md) / 150) * 0.4).toFixed(3) + ')';
                x.beginPath(); x.moveTo(pts[a].x, pts[a].y); x.lineTo(m.x, m.y); x.stroke();
            }
        }
        if (run) { requestAnimationFrame(loop); }
    }
    loop();
    document.addEventListener('visibilitychange', function () { run = !document.hidden; if (run) { loop(); } });
})();
</script>