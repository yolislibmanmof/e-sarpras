<?php
$dbSide = \App\Core\Database::instance();
$pendingTickets  = $dbSide->count('tickets', ['status' => 'Menunggu Verifikasi']);
$pendingBorrows  = $dbSide->count('borrow_requests', ['status' => 'Menunggu Verifikasi']);
$pendingRequests = $dbSide->count('item_requests', ['status' => 'Menunggu Verifikasi']);
$uri = current_uri();
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <span class="brand-mark">eS</span>
        <span class="label"><?= e(config('app.name')); ?></span>
    </div>

    <nav class="sidebar-nav">
        <a class="side-link<?= ($uri === '/dashboard' || $uri === '/') ? ' active' : ''; ?>" href="<?= admin_url('/dashboard'); ?>" title="Dashboard">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
            <span class="label">Dashboard</span>
        </a>

        <div class="side-group">Master Data</div>
        <a class="side-link<?= strpos($uri, '/gedung') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/gedung'); ?>" title="Gedung">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4"/></svg>
            <span class="label">Gedung</span>
        </a>
        <a class="side-link<?= strpos($uri, '/ruangan') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/ruangan'); ?>" title="Ruangan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-4h6v4"/></svg>
            <span class="label">Ruangan</span>
        </a>
        <a class="side-link<?= strpos($uri, '/aset') === 0 && strpos($uri, '/aset/penyusutan') !== 0 ? ' active' : ''; ?>" href="<?= admin_url('/aset'); ?>" title="Aset">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
            <span class="label">Aset</span>
        </a>
        <a class="side-link<?= strpos($uri, '/aset-kategori') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/aset-kategori'); ?>" title="Kategori Aset">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1"/></svg>
            <span class="label">Kategori Aset</span>
        </a>
        <a class="side-link<?= strpos($uri, '/aset/penyusutan') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/aset/penyusutan'); ?>" title="Penyusutan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m7 14 4-4 4 3 5-6"/></svg>
            <span class="label">Penyusutan Aset</span>
        </a>

        <div class="side-group">Operasional</div>
        <a class="side-link<?= strpos($uri, '/tiket') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/tiket'); ?>" title="Tiket">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.3 3.8 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.8a2 2 0 0 0-3.4 0z"/></svg>
            <span class="label">Tiket Kerusakan</span>
            <?php if ($pendingTickets > 0): ?><span class="side-badge"><?= (int) $pendingTickets; ?></span><?php endif; ?>
        </a>
        <a class="side-link<?= strpos($uri, '/pemeliharaan') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/pemeliharaan'); ?>" title="Pemeliharaan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a4.5 4.5 0 0 0-6.4 6.4L3 18v3h3l5.3-5.3a4.5 4.5 0 0 0 6.4-6.4l-2.9 2.9-2-2 2.9-2.9z"/></svg>
            <span class="label">Pemeliharaan</span>
        </a>
        <a class="side-link<?= strpos($uri, '/peminjaman-barang') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/peminjaman-barang'); ?>" title="Peminjaman Barang">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
            <span class="label">Peminjaman Barang</span>
            <?php if ($pendingBorrows > 0): ?><span class="side-badge"><?= (int) $pendingBorrows; ?></span><?php endif; ?>
        </a>
        <a class="side-link<?= strpos($uri, '/peminjaman-ruangan') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/peminjaman-ruangan'); ?>" title="Peminjaman Ruangan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/></svg>
            <span class="label">Peminjaman Ruangan</span>
        </a>
        <a class="side-link<?= strpos($uri, '/permintaan-barang') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/permintaan-barang'); ?>" title="Permintaan Barang">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
            <span class="label">Permintaan Barang</span>
            <?php if ($pendingRequests > 0): ?><span class="side-badge"><?= (int) $pendingRequests; ?></span><?php endif; ?>
        </a>
        <a class="side-link<?= strpos($uri, '/stok') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/stok'); ?>" title="Stok">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="M3.3 7 12 12l8.7-5"/><path d="M12 22V12"/></svg>
            <span class="label">Stok Gudang</span>
        </a>
        <a class="side-link<?= strpos($uri, '/teknisi') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/teknisi'); ?>" title="Teknisi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/></svg>
            <span class="label">Teknisi</span>
        </a>
        <a class="side-link<?= strpos($uri, '/vendor') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/vendor'); ?>" title="Vendor">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v11h16V9"/><path d="M9 20v-6h6v6"/></svg>
            <span class="label">Vendor</span>
        </a>

        <div class="side-group">K3L</div>
        <a class="side-link<?= strpos($uri, '/k3/apar') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/k3/apar'); ?>" title="APAR">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2h8"/><path d="M12 2v5"/><path d="M7 7h10v15H7z"/></svg>
            <span class="label">APAR</span>
        </a>
        <a class="side-link<?= strpos($uri, '/k3/limbah') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/k3/limbah'); ?>" title="Limbah">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>
            <span class="label">Limbah</span>
        </a>
        <a class="side-link<?= strpos($uri, '/k3/simulasi') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/k3/simulasi'); ?>" title="Simulasi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span class="label">Simulasi Bencana</span>
        </a>

        <div class="side-group">Administrasi</div>
        <a class="side-link<?= strpos($uri, '/surat') === 0 || strpos($uri, '/disposisi') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/surat'); ?>" title="Surat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>
            <span class="label">Surat &amp; Disposisi</span>
        </a>
        <a class="side-link<?= strpos($uri, '/template-surat') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/template-surat'); ?>" title="Template">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
            <span class="label">Template Surat</span>
        </a>
        <a class="side-link<?= strpos($uri, '/survei') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/survei'); ?>" title="Survei">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span class="label">Survei Kepuasan</span>
        </a>
        <a class="side-link<?= strpos($uri, '/laporan') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/laporan'); ?>" title="Laporan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M8 17v-6"/><path d="M13 17V7"/><path d="M18 17v-4"/></svg>
            <span class="label">Laporan</span>
        </a>

        <div class="side-group">Sistem</div>
        <a class="side-link<?= strpos($uri, '/pengguna') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/pengguna'); ?>" title="Pengguna">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span class="label">Pengguna</span>
        </a>
        <a class="side-link<?= strpos($uri, '/role') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/role'); ?>" title="Role & Izin">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <span class="label">Role &amp; Izin</span>
        </a>
        <a class="side-link<?= strpos($uri, '/pengaturan') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/pengaturan'); ?>" title="Pengaturan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h0a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h0a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v0a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            <span class="label">Pengaturan</span>
        </a>
        <a class="side-link<?= strpos($uri, '/audit-log') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/audit-log'); ?>" title="Audit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
            <span class="label">Audit Log</span>
        </a>
        <a class="side-link<?= strpos($uri, '/backup') === 0 ? ' active' : ''; ?>" href="<?= admin_url('/backup'); ?>" title="Backup">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
            <span class="label">Backup</span>
        </a>
    </nav>
</aside>