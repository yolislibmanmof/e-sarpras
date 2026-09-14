<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>
<?php $errors = \App\Core\Session::getFlash('errors') ?? []; ?>
<?php
$dbWow = \App\Core\Database::instance();
$totalStock = (int) $dbWow->count('stocks');
$lowCount = (int) ($dbWow->selectOne("SELECT COUNT(*) AS t FROM stocks WHERE quantity <= minimum_quantity")['t'] ?? 0);
$health = $totalStock > 0 ? 1 - ($lowCount / $totalStock) : 1;
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Permintaan Barang</span>
        <h1>Permintaan Barang (ATK / Elektronik)</h1>
        <p>Formulir permintaan barang habis pakai maupun barang elektronik untuk keperluan dosen, prodi, fakultas, atau unit.</p>
    </div>
</section>

<section class="section" style="padding:2.2rem 0 0;">
    <div class="container">
        <div class="ring-wrap">
            <div class="ring" style="--off:<?= (int) (264 * (1 - $health)); ?>">
                <svg width="92" height="92"><circle class="bgc" cx="46" cy="46" r="42"/><circle class="fgc" cx="46" cy="46" r="42"/></svg>
                <div class="val"><?= (int) round($health * 100); ?>%<small>Kesehatan Stok</small></div>
            </div>
            <div class="ring" style="--off:264">
                <svg width="92" height="92"><circle class="bgc" cx="46" cy="46" r="42"/><circle class="fgc" cx="46" cy="46" r="42" style="stroke-dashoffset:<?= (int) (264 * ($totalStock > 0 ? (1 - $lowCount / $totalStock) : 0)); ?>;animation:none;"/></svg>
                <div class="val"><?= $lowCount; ?><small>Kategori Menipis</small></div>
            </div>
        </div>
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

            <div class="public-form-card shine">
                <form method="POST" action="<?= base_url('/permintaan-barang'); ?>">
                    <?= csrf_field(); ?>
                    <div class="form-grid">
                        <div><label for="requester_name">Nama Pemohon *</label><input id="requester_name" name="requester_name" value="<?= e($old['requester_name'] ?? ''); ?>" required></div>
                        <div>
                            <label for="requester_type">Status</label>
                            <select id="requester_type" name="requester_type">
                                <?php foreach ($requesterTypes as $type): ?>
                                    <option value="<?= e($type); ?>" <?= ($old['requester_type'] ?? 'Dosen') === $type ? 'selected' : ''; ?>><?= e($type); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div><label for="unit_name">Unit / Prodi / Fakultas *</label><input id="unit_name" name="unit_name" value="<?= e($old['unit_name'] ?? ''); ?>" required></div>
                        <div>
                            <label for="request_type">Jenis Permintaan</label>
                            <select id="request_type" name="request_type">
                                <option value="ATK" <?= ($old['request_type'] ?? 'ATK') === 'ATK' ? 'selected' : ''; ?>>ATK / Habis Pakai</option>
                                <option value="Elektronik" <?= ($old['request_type'] ?? '') === 'Elektronik' ? 'selected' : ''; ?>>Barang Elektronik</option>
                            </select>
                        </div>
                        <div><label for="needed_date">Tanggal Dibutuhkan</label><input id="needed_date" name="needed_date" type="date" value="<?= e($old['needed_date'] ?? ''); ?>"></div>
                        <div class="form-full"><label for="purpose">Keperluan *</label><textarea id="purpose" name="purpose" rows="2" required><?= e($old['purpose'] ?? ''); ?></textarea></div>
                        <div class="form-full">
                            <label>Daftar Barang * (maksimal 3 baris)</label>
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <div class="item-row-3">
                                    <select name="items[<?= $i; ?>][stock_id]">
                                        <option value="">Pilih dari stok (opsional)</option>
                                        <?php foreach ($stocks as $stock): ?>
                                            <option value="<?= (int) $stock['id']; ?>"><?= e($stock['item_code'] . ' - ' . $stock['item_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="items[<?= $i; ?>][item_name]" placeholder="Atau tulis nama barang">
                                    <input type="number" name="items[<?= $i; ?>][quantity]" min="1" value="1" placeholder="Jumlah">
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Kirim Permintaan</button>
                    </div>
                </form>
            </div>
        </div>

        <aside class="side-panel">
            <div class="card info-card shine">
                <h4><span class="live-dot"></span> Stok Menipis</h4>
                <?php if ($lowStock === []): ?>
                    <p>Seluruh stok dalam kondisi aman.</p>
                <?php else: ?>
                    <ul class="mini-steps">
                        <?php foreach ($lowStock as $stock): ?>
                            <li><b>!</b> <strong><?= e($stock['item_name']); ?></strong> &mdash; sisa <?= (float) $stock['quantity']; ?> (min. <?= (float) $stock['minimum_quantity']; ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="card info-card">
                <h4>Alur Permintaan</h4>
                <ul class="mini-steps">
                    <li><b>1</b> Permintaan diverifikasi Sarpras.</li>
                    <li><b>2</b> Persetujuan pejabat berwenang.</li>
                    <li><b>3</b> Barang diserahkan dari stok gudang.</li>
                </ul>
            </div>
        </aside>
    </div>
</section>

<?php if ($code !== '' && $result !== null): ?>
<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="public-form-card shine">
            <div class="tracking-head">
                <div>
                    <h2><?= e($result['request_code']); ?></h2>
                    <p><?= e($result['request_type']); ?> &middot; <?= e($result['requester_name']); ?> (<?= e($result['unit_name']); ?>)</p>
                </div>
                <span class="badge <?= e(status_badge_class($result['status'])); ?>"><?= e($result['status']); ?></span>
            </div>
            <p class="tracking-eta">Status persetujuan: <strong><?= e($result['approval_status']); ?></strong></p>
        </div>
    </div>
</section>
<?php elseif ($code !== '' && $result === null): ?>
<section class="section" style="padding-top:0;">
    <div class="container"><div class="public-alert public-alert-error">Kode permintaan tidak ditemukan.</div></div>
</section>
<?php endif; ?>