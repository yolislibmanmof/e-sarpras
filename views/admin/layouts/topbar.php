<header class="topbar">
    <button class="topbar-icon" id="sidebarCollapse" type="button" title="Ringkas sidebar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m11 17-5-5 5-5"/><path d="m18 17-5-5 5-5"/></svg>
    </button>
    <button class="topbar-icon" id="sidebarToggle" type="button" aria-label="Buka menu" style="display:none;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18"/><path d="M3 12h18"/><path d="M3 18h18"/></svg>
    </button>

    <div class="topbar-title"><?= e($title ?? 'Dashboard'); ?></div>

    <div class="topbar-actions">
        <span class="topbar-clock" id="topbarClock">00:00:00</span>

        <button class="topbar-search" id="paletteOpen" type="button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <span>Cari modul...</span>
            <kbd>Ctrl K</kbd>
        </button>

        <button class="topbar-icon" id="themeToggle" type="button" title="Ganti tema">
            <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.9 4.9 1.4 1.4"/><path d="m17.7 17.7 1.4 1.4"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.3 17.7-1.4 1.4"/><path d="m19.1 4.9-1.4 1.4"/></svg>
            <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9z"/></svg>
        </button>

        <button class="topbar-icon" id="fullscreenToggle" type="button" title="Layar penuh">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 3H5a2 2 0 0 0-2 2v3"/><path d="M21 8V5a2 2 0 0 0-2-2h-3"/><path d="M3 16v3a2 2 0 0 0 2 2h3"/><path d="M16 21h3a2 2 0 0 0 2-2v-3"/></svg>
        </button>

        <a class="topbar-icon" href="<?= admin_url('/tiket'); ?>" title="Tiket menunggu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
            <span class="bell-dot"></span>
        </a>

        <div class="topbar-user">
            <span class="avatar"><?= e(strtoupper(substr($user['full_name'] ?? 'A', 0, 1))); ?></span>
            <span class="topbar-info">
                <b><?= e($user['full_name'] ?? ''); ?></b>
                <small><?= e(implode(', ', $roles ?? [])); ?></small>
            </span>
        </div>

        <a class="topbar-icon" href="<?= admin_url('/logout'); ?>" title="Keluar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/></svg>
        </a>
    </div>
</header>
<style>@media (max-width:900px){#sidebarToggle{display:flex!important}}</style>