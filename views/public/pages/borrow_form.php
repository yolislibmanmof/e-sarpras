<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$old = \App\Core\Session::getFlash('old') ?? [];
$errors = \App\Core\Session::getFlash('errors') ?? [];
$dbB = \App\Core\Database::instance();
$topItems = $dbB->select(
    "SELECT a.code, a.name, COUNT(bi.id) AS c
     FROM borrow_items bi
     JOIN assets a ON a.id = bi.asset_id
     GROUP BY bi.asset_id
     ORDER BY c DESC LIMIT 5"
);
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/peminjaman'); ?>">Peminjaman</a> &rarr; Barang</span>
        <h1>Form Peminjaman Barang</h1>
        <p>Lengkapi data peminjam dan barang yang akan dipinjam.</p>
        <p style="margin-top:.8rem;"><span class="badge badge-success"><span class="live-dot" style="margin-right:.4rem;"></span>Maksimal 3 jenis barang per pengajuan</span></p>
    </div>
</section>

<section class="section page-body">
    <div class="container split-grid">
        <div>
            <?php if ($errors !== []): ?>
                <div class="public-alert public-alert-error">
                    <ul><?php foreach ($errors as $list): foreach ($list as $m): ?><li><?= e($m); ?></li><?php endforeach; endforeach; ?></ul>
                </div>
            <?php endif; ?>

            <!-- Kartu ringkasan hidup -->
            <div class="card shine" style="padding:1rem 1.2rem;margin-bottom:1.2rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
                    <div>
                        <strong style="color:var(--primary-dark);" id="sumTitle">Ringkasan Pengajuan</strong>
                        <div style="font-size:.8rem;color:var(--muted);" id="sumMeta">Pilih tanggal dan barang untuk melihat ringkasan.</div>
                    </div>
                    <span class="badge badge-info" id="sumBadge">Menunggu pilihan...</span>
                </div>
            </div>

            <div class="public-form-card shine">
                <form method="POST" action="<?= base_url('/peminjaman/barang'); ?>" id="borrowForm">
                    <?= csrf_field(); ?>
                    <div class="form-grid">
                        <div><label for="borrower_name">Nama Peminjam *</label><input id="borrower_name" name="borrower_name" value="<?= e($old['borrower_name'] ?? ''); ?>" required></div>
                        <div>
                            <label for="borrower_type">Status</label>
                            <select id="borrower_type" name="borrower_type">
                                <?php foreach ($borrowerTypes as $type): ?>
                                    <option value="<?= e($type); ?>" <?= ($old['borrower_type'] ?? 'Mahasiswa') === $type ? 'selected' : ''; ?>><?= e($type); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div><label for="unit_name">Unit / Prodi / Fakultas</label><input id="unit_name" name="unit_name" value="<?= e($old['unit_name'] ?? ''); ?>"></div>
                        <div><label for="phone">No. HP *</label><input id="phone" name="phone" value="<?= e($old['phone'] ?? ''); ?>" required></div>
                        <div><label for="email">Email</label><input id="email" name="email" type="email" value="<?= e($old['email'] ?? ''); ?>"></div>
                        <div><label for="event_name">Nama Kegiatan</label><input id="event_name" name="event_name" value="<?= e($old['event_name'] ?? ''); ?>"></div>
                        <div><label for="borrow_date">Tanggal Pinjam *</label><input id="borrow_date" name="borrow_date" type="date" value="<?= e($old['borrow_date'] ?? ''); ?>" required></div>
                        <div><label for="expected_return_date">Tanggal Kembali *</label><input id="expected_return_date" name="expected_return_date" type="date" value="<?= e($old['expected_return_date'] ?? ''); ?>" required></div>
                        <div class="form-full"><label for="location">Lokasi Penggunaan</label><input id="location" name="location" value="<?= e($old['location'] ?? ''); ?>"></div>
                        <div class="form-full"><label for="purpose">Keperluan *</label><textarea id="purpose" name="purpose" rows="2" required><?= e($old['purpose'] ?? ''); ?></textarea></div>
                        <div class="form-full">
                            <label>Barang yang Dipinjam * (maksimal 3 baris)</label>
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <div class="item-row" style="display:flex;gap:.6rem;align-items:center;margin-bottom:.6rem;">
                                    <span class="sv-num" style="width:26px;height:26px;border-radius:9px;background:linear-gradient(135deg,var(--primary),var(--accent-2));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;flex-shrink:0;"><?= $i + 1; ?></span>
                                    <select name="items[<?= $i; ?>][asset_id]" style="flex:1;">
                                        <option value="">Pilih barang</option>
                                        <?php foreach ($assets as $asset): ?>
                                            <option value="<?= (int) $asset['id']; ?>"><?= e($asset['code'] . ' - ' . $asset['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="number" name="items[<?= $i; ?>][quantity]" min="1" value="1" placeholder="Jumlah" style="width:90px;">
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                        <a class="btn btn-outline" href="<?= base_url('/peminjaman'); ?>">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        <aside class="side-panel">
            <div class="card info-card shine">
                <h4><span class="live-dot"></span> Aset Tersedia</h4>
                <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
                    <?php foreach ($available as $asset): ?>
                        <span class="badge badge-info"><?= e($asset['code']); ?> &middot; <?= e($asset['name']); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card info-card shine">
                <h4>Paling Sering Dipinjam</h4>
                <?php if ($topItems === []): ?>
                    <p>Belum ada riwayat peminjaman.</p>
                <?php else: ?>
                    <ul class="mini-steps">
                        <?php foreach ($topItems as $t): ?>
                            <li><b>&#9733;</b> <strong><?= e($t['name']); ?></strong><br><span style="color:var(--muted);font-size:.78rem;"><?= e($t['code']); ?> &middot; <?= (int) $t['c']; ?> kali dipinjam</span></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="card info-card">
                <h4>Alur Persetujuan</h4>
                <ul class="mini-steps">
                    <li><b>1</b> Pengajuan diterima dan diverifikasi Sarpras.</li>
                    <li><b>2</b> Persetujuan oleh pejabat berwenang.</li>
                    <li><b>3</b> Barang diserahkan (status "Dipinjam").</li>
                    <li><b>4</b> Pengembalian dan pemeriksaan kondisi.</li>
                </ul>
            </div>
        </aside>
    </div>
</section>

<script>
(function () {
    var form = document.getElementById('borrowForm');
    if (!form) { return; }
    var bIn = form.querySelector('#borrow_date');
    var rIn = form.querySelector('#expected_return_date');
    var meta = document.getElementById('sumMeta');
    var badge = document.getElementById('sumBadge');

    function refresh() {
        var items = form.querySelectorAll('select[name^="items"]');
        var chosen = 0;
        items.forEach(function (s) { if (s.value !== '') { chosen++; } });

        var parts = [];
        parts.push(chosen + ' jenis barang');

        if (bIn.value && rIn.value) {
            var b = new Date(bIn.value);
            var r = new Date(rIn.value);
            var days = Math.round((r - b) / 86400000);
            if (days < 0) {
                badge.textContent = 'Tanggal kembali tidak valid!';
                badge.className = 'badge badge-danger';
                meta.textContent = parts.join(' · ');
                return;
            }
            parts.push('durasi ' + (days + 1) + ' hari');
            badge.textContent = days === 0 ? 'Pinjam harian' : 'Siap diajukan';
            badge.className = 'badge badge-success';
        } else {
            badge.textContent = 'Lengkapi tanggal';
            badge.className = 'badge badge-warning';
        }
        meta.textContent = parts.join(' · ');
    }

    form.addEventListener('change', refresh);
    form.addEventListener('input', refresh);
    refresh();
})();
</script>