<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>
<?php $errors = \App\Core\Session::getFlash('errors') ?? []; ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/peminjaman'); ?>">Peminjaman</a> &rarr; Barang</span>
        <h1>Form Peminjaman Barang</h1>
        <p>Lengkapi data peminjam dan barang yang akan dipinjam.</p>
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
                <form method="POST" action="<?= base_url('/peminjaman/barang'); ?>">
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
                                <div class="item-row">
                                    <select name="items[<?= $i; ?>][asset_id]">
                                        <option value="">Pilih barang</option>
                                        <?php foreach ($assets as $asset): ?>
                                            <option value="<?= (int) $asset['id']; ?>"><?= e($asset['code'] . ' - ' . $asset['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="number" name="items[<?= $i; ?>][quantity]" min="1" value="1" placeholder="Jumlah">
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