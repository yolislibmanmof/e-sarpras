<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>
<?php $isEdit = $stock !== null; ?>
<?php $current = $isEdit ? $stock + $old : $old; ?>

<div class="page-head">
    <h1><?= $isEdit ? 'Ubah Stok' : 'Tambah Stok'; ?></h1>
    <p>Kelola kartu stok barang gudang Sarpras.</p>
</div>

<?php require base_path('views/admin/components/form_errors.php'); ?>

<div class="form-card">
    <form method="POST" action="<?= $isEdit ? admin_url('/stok/' . $stock['id'] . '/ubah') : admin_url('/stok'); ?>">
        <?= csrf_field(); ?>

        <div class="form-grid">
            <div>
                <label for="item_code">Kode Barang *</label>
                <input id="item_code" name="item_code" value="<?= e($current['item_code'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="item_name">Nama Barang *</label>
                <input id="item_name" name="item_name" value="<?= e($current['item_name'] ?? ''); ?>" required>
            </div>

            <div>
                <label for="stock_category_id">Kategori *</label>
                <select id="stock_category_id" name="stock_category_id" required>
                    <option value="">Pilih kategori</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int) $c['id']; ?>" <?= (string) ($current['stock_category_id'] ?? '') === (string) $c['id'] ? 'selected' : ''; ?>><?= e($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="unit">Satuan</label>
                <input id="unit" name="unit" value="<?= e($current['unit'] ?? ''); ?>" placeholder="Contoh: pcs, box, rim">
            </div>

            <div>
                <label for="quantity">Jumlah Stok</label>
                <input id="quantity" name="quantity" type="number" step="0.01" value="<?= e($current['quantity'] ?? 0); ?>">
            </div>
            <div>
                <label for="minimum_quantity">Stok Minimum</label>
                <input id="minimum_quantity" name="minimum_quantity" type="number" step="0.01" value="<?= e($current['minimum_quantity'] ?? 0); ?>">
            </div>

            <div>
                <label for="location">Lokasi Penyimpanan</label>
                <input id="location" name="location" value="<?= e($current['location'] ?? ''); ?>">
            </div>
            <div>
                <label for="condition">Kondisi</label>
                <select id="condition" name="condition">
                    <?php foreach (['Baik', 'Rusak Ringan', 'Rusak Berat'] as $option): ?>
                        <option value="<?= e($option); ?>" <?= ($current['condition'] ?? 'Baik') === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="checkbox-row" style="align-self:end;">
                <input type="checkbox" id="is_borrowable" name="is_borrowable" <?= (int) ($current['is_borrowable'] ?? 0) === 1 ? 'checked' : ''; ?>>
                <label for="is_borrowable">Dapat Dipinjamkan</label>
            </div>

            <div class="form-full">
                <label for="specification">Spesifikasi</label>
                <textarea id="specification" name="specification" rows="2"><?= e($current['specification'] ?? ''); ?></textarea>
            </div>

            <div class="form-full">
                <label for="notes">Catatan</label>
                <textarea id="notes" name="notes" rows="2"><?= e($current['notes'] ?? ''); ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a class="btn btn-secondary" href="<?= admin_url('/stok'); ?>">Kembali</a>
        </div>
    </form>
</div>

<?php if ($isEdit): ?>
<div class="grid-2" style="margin-top:1.4rem;">
    <div class="card">
        <h3 class="card-title">Mutasi Stok</h3>
        <form method="POST" action="<?= admin_url('/stok/' . $stock['id'] . '/mutasi'); ?>">
            <?= csrf_field(); ?>
            <div class="form-grid">
                <div>
                    <label for="movement_type">Jenis Mutasi</label>
                    <select id="movement_type" name="movement_type" required>
                        <option value="in">Masuk (penambahan)</option>
                        <option value="out">Keluar (pengurangan)</option>
                    </select>
                </div>
                <div>
                    <label for="quantity_move">Jumlah</label>
                    <input id="quantity_move" name="quantity" type="number" step="0.01" min="0.01" required>
                </div>
                <div class="form-full">
                    <label for="move_notes">Catatan</label>
                    <input id="move_notes" name="notes" placeholder="Contoh: Pembelian rutin, pemakaian internal">
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary btn-sm" type="submit">Catat Mutasi</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h3 class="card-title">Riwayat Mutasi</h3>
        <?php if ($movements === []): ?>
            <p class="empty-note">Belum ada mutasi.</p>
        <?php else: ?>
            <ul class="list-plain">
                <?php foreach ($movements as $movement): ?>
                    <li>
                        <span>
                            <span class="badge <?= $movement['movement_type'] === 'in' ? 'badge-success' : 'badge-warning'; ?>"><?= $movement['movement_type'] === 'in' ? 'Masuk' : 'Keluar'; ?></span>
                            <?= (float) $movement['quantity']; ?> &mdash; <?= e($movement['notes'] ?? ''); ?>
                        </span>
                        <span><?= e(format_tanggal_waktu($movement['movement_date'])); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>