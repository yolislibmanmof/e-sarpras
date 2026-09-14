<?php
$logo = $header['letter_logo'] ?? '';
$logoPath = $logo !== '' ? rtrim(config('upload.base_path'), '/') . '/' . $logo : '';
?>

<div class="page-head">
    <h1>Template Surat &amp; Kop Resmi</h1>
    <p>Kelola kop surat resmi, desain surat, dan template isi surat.</p>
</div>

<!-- ================================================================ -->
<!-- KOPI SURAT RESMI                                                   -->
<!-- ================================================================ -->
<div class="form-card" style="max-width:100%;">
    <h3 class="card-title">Kop Surat Resmi</h3>
    <p style="margin-bottom:1.2rem;">Identitas kop diterapkan seragam pada seluruh cetakan surat keluar.</p>

    <form method="POST" action="<?= admin_url('/template-surat/kop'); ?>" enctype="multipart/form-data">
        <?= csrf_field(); ?>
        <div class="form-grid">
            <div>
                <label for="letter_logo">Logo Surat</label>
                <input id="letter_logo" name="letter_logo" type="file" accept=".png,.jpg,.jpeg,.svg">
                <?php if ($logoPath !== '' && is_file($logoPath)): ?>
                    <img src="<?= base_url('/media/' . $logo); ?>" alt="Logo surat" style="width:70px;height:70px;border-radius:10px;object-fit:cover;margin-top:.6rem;border:1px solid var(--border);">
                <?php endif; ?>
                <p class="file-note">Logo khusus surat (terpisah dari logo publik).</p>
            </div>
            <div>
                <label for="letter_website">Website Kampus</label>
                <input id="letter_website" name="letter_website" value="<?= e($header['letter_website'] ?? ''); ?>" placeholder="www.kampus.ac.id">
            </div>
            <div class="form-full">
                <label for="letter_address">Alamat Lengkap</label>
                <textarea id="letter_address" name="letter_address" rows="2"><?= e($header['letter_address'] ?? setting_value('campus_address', '')); ?></textarea>
            </div>
            <div>
                <label for="letter_phone">Nomor Telepon</label>
                <input id="letter_phone" name="letter_phone" value="<?= e($header['letter_phone'] ?? setting_value('campus_phone', '')); ?>">
            </div>
            <div>
                <label for="letter_email">Email Resmi</label>
                <input id="letter_email" name="letter_email" type="email" value="<?= e($header['letter_email'] ?? setting_value('campus_email', '')); ?>">
            </div>
        </div>
        <div class="form-actions"><button type="submit" class="btn btn-primary">Simpan Kop Surat</button></div>
    </form>
</div>

<!-- ================================================================ -->
<!-- DESAIN SURAT (EDITOR LIVE)                                         -->
<!-- ================================================================ -->
<div class="form-card" style="max-width:100%;margin-top:1.4rem;">
    <h3 class="card-title">Desain Surat &mdash; Editor Live</h3>
    <p style="margin-bottom:1.2rem;">Atur tipografi, spasi, margin, kop, dan tanda tangan sesuai tata naskah dinas. Pratinjau <strong>satu halaman penuh</strong> diperbarui seketika; klik <strong>Simpan Desain</strong> untuk menerapkan ke seluruh cetakan.</p>

    <div class="designer">
        <form id="designForm" method="POST" action="<?= admin_url('/template-surat/kop'); ?>" class="designer-controls">
            <?= csrf_field(); ?>

            <h4 class="sub-title">Tipografi</h4>
            <label for="d_font_family">Keluarga Font</label>
            <select id="d_font_family" name="d[font_family]">
                <?php foreach (['Times New Roman', 'Arial', 'Calibri', 'Georgia', 'Bookman Old Style', 'Tahoma'] as $f): ?>
                    <option value="<?= e($f); ?>" <?= $d['font_family'] === $f ? 'selected' : ''; ?>><?= e($f); ?></option>
                <?php endforeach; ?>
            </select>
            <div class="form-grid" style="margin-top:.6rem;">
                <div><label for="d_font_size">Ukuran (pt)</label><input id="d_font_size" name="d[font_size]" type="number" min="9" max="16" value="<?= e($d['font_size']); ?>"></div>
                <div><label for="d_line_height">Tinggi Baris</label><input id="d_line_height" name="d[line_height]" type="number" step="0.1" min="1" max="2.5" value="<?= e($d['line_height']); ?>"></div>
            </div>

            <h4 class="sub-title">Spasi &amp; Margin (mm)</h4>
            <div class="form-grid">
                <div><label for="d_space_after_kop">Spasi setelah kop</label><input id="d_space_after_kop" name="d[space_after_kop]" type="number" min="0" max="40" value="<?= e($d['space_after_kop']); ?>"></div>
                <div><label for="d_space_paragraph">Spasi antar paragraf</label><input id="d_space_paragraph" name="d[space_paragraph]" type="number" min="0" max="20" value="<?= e($d['space_paragraph']); ?>"></div>
                <div><label for="d_margin_top">Margin atas/bawah</label><input id="d_margin_top" name="d[margin_top]" type="number" min="10" max="40" value="<?= e($d['margin_top']); ?>"></div>
                <div><label for="d_margin_side">Margin kiri/kanan</label><input id="d_margin_side" name="d[margin_side]" type="number" min="10" max="40" value="<?= e($d['margin_side']); ?>"></div>
            </div>

            <h4 class="sub-title">Kop &amp; Isi</h4>
            <label for="d_kop_dept">Baris Unit (Kop)</label>
            <input id="d_kop_dept" name="d[kop_dept]" value="<?= e($d['kop_dept']); ?>">
            <label for="d_kop_border">Garis Kop</label>
            <select id="d_kop_border" name="d[kop_border]">
                <option value="double" <?= $d['kop_border'] === 'double' ? 'selected' : ''; ?>>Garis ganda (standar dinas)</option>
                <option value="thick" <?= $d['kop_border'] === 'thick' ? 'selected' : ''; ?>>Garis tebal</option>
                <option value="thin" <?= $d['kop_border'] === 'thin' ? 'selected' : ''; ?>>Garis tipis</option>
            </select>
            <div class="checkbox-row"><input type="checkbox" id="d_show_logo" name="d[show_logo]" <?= $d['show_logo'] === '1' ? 'checked' : ''; ?>><label for="d_show_logo">Tampilkan logo pada kop</label></div>
            <div class="checkbox-row"><input type="checkbox" id="d_show_meta" name="d[show_meta]" <?= $d['show_meta'] === '1' ? 'checked' : ''; ?>><label for="d_show_meta">Tampilkan blok Nomor/Lampiran/Perihal</label></div>

            <h4 class="sub-title">Tanda Tangan</h4>
            <label for="d_sign_title">Jabatan Penandatangan</label>
            <input id="d_sign_title" name="d[sign_title]" value="<?= e($d['sign_title']); ?>">
            <div class="checkbox-row"><input type="checkbox" id="d_show_paraf" name="d[show_paraf]" <?= $d['show_paraf'] === '1' ? 'checked' : ''; ?>><label for="d_show_paraf">Tampilkan ruang paraf</label></div>
            <div class="checkbox-row"><input type="checkbox" id="d_show_nip" name="d[show_nip]" <?= $d['show_nip'] === '1' ? 'checked' : ''; ?>><label for="d_show_nip">Tampilkan NIP</label></div>

            <div class="form-actions"><button type="submit" class="btn btn-primary">Simpan Desain</button></div>
        </form>

        <div class="designer-preview">
            <div class="pv-sheet" id="pvSheet">
                <div class="pv-kop" id="pvKop">
                    <div class="pv-logo" id="pvLogo">
                        <?php if ($logoPath !== '' && is_file($logoPath)): ?>
                            <img src="<?= base_url('/media/' . $logo); ?>" alt="Logo">
                        <?php else: ?>
                            <span style="border:1.5px solid #000;padding:14px 10px;font-family:Arial;font-size:9pt;font-weight:bold;">LOGO</span>
                        <?php endif; ?>
                    </div>
                    <div class="pv-kop-text">
                        <div class="pv-l1"><?= e(setting_value('campus_name', 'NAMA KAMPUS')); ?></div>
                        <div class="pv-l2" id="pvL2"><?= e($d['kop_dept']); ?></div>
                        <div class="pv-l3" id="pvL3"><?= e($header['letter_address'] ?? setting_value('campus_address', '')); ?></div>
                    </div>
                </div>

                <div class="pv-meta" id="pvMeta">
                    <table>
                        <tr><td>Nomor</td><td>: B/001/SARPRAS/VIII/2026</td></tr>
                        <tr><td>Lampiran</td><td>: 1 berkas</td></tr>
                        <tr><td>Perihal</td><td>: <strong>Permohonan Peminjaman Barang</strong></td></tr>
                    </table>
                </div>

                <p class="pv-p">Kepada Yth.<br><strong>Bapak/Ibu Kepala Unit</strong><br>di Tempat</p>
                <p class="pv-p">Dengan hormat,</p>
                <p class="pv-p">Berdasarkan permohonan yang diajukan, bersama ini kami sampaikan persetujuan peminjaman barang sebagaimana rincian terlampir. Demikian surat ini kami sampaikan untuk dipergunakan sebagaimana mestinya.</p>
                <p class="pv-p">Atas perhatian dan kerja sama yang baik, kami ucapkan terima kasih.</p>

                <div class="pv-sign">
                    <div class="pv-sign-place">Maumere, 25 Agustus 2026</div>
                    <div class="pv-sign-title" id="pvSignTitle"><?= e($d['sign_title']); ?>,</div>
                    <div class="pv-paraf" id="pvParaf">(paraf)</div>
                    <div class="pv-sign-name"><?= e(setting_value('sarpras_head_name', 'Nama Kepala')); ?></div>
                    <div class="pv-sign-nip" id="pvNip">NIP. <?= e(setting_value('sarpras_head_nip', '-')); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.designer{display:grid;grid-template-columns:340px 1fr;gap:1.3rem}
.designer-controls label{font-size:.8rem}
.designer-controls input,.designer-controls select{margin-bottom:.4rem}
.designer-preview{background:#dfe7e4;border-radius:14px;padding:1.1rem;overflow:auto;max-height:820px}
.pv-sheet{background:#fff;box-shadow:0 14px 40px rgba(6,56,46,.25);min-height:760px;margin:0 auto;max-width:640px}
.pv-kop{display:flex;gap:12px;align-items:center;padding-bottom:8px}
.pv-kop-text{flex:1;text-align:center}
.pv-l1{font-weight:800}
.pv-l2{font-weight:700}
.pv-l3{font-size:.72em;line-height:1.5}
.pv-meta table td{padding:1px 6px 1px 0}
.pv-sign{margin-top:2em;text-align:right}
.pv-paraf{height:3.2em;color:#888}
@media (max-width:1000px){.designer{grid-template-columns:1fr}}
</style>

<script>
(function () {
    var form = document.getElementById('designForm');
    if (!form) { return; }
    var sheet = document.getElementById('pvSheet');
    var kop = document.getElementById('pvKop');
    var logo = document.getElementById('pvLogo');
    var l2 = document.getElementById('pvL2');
    var meta = document.getElementById('pvMeta');
    var signTitle = document.getElementById('pvSignTitle');
    var paraf = document.getElementById('pvParaf');
    var nip = document.getElementById('pvNip');

    var borders = { double: '3px double #000', thick: '4px solid #000', thin: '1.5px solid #000' };

    function v(n) { var el = form.elements[n]; return el ? el.value : ''; }
    function c(n) { var el = form.elements[n]; return el ? el.checked : false; }

    function refresh() {
        sheet.style.fontFamily = '"' + v('d[font_family]') + '", serif';
        sheet.style.fontSize = v('d[font_size]') + 'pt';
        sheet.style.lineHeight = v('d[line_height]');
        sheet.style.padding = v('d[margin_top]') + 'mm ' + v('d[margin_side]') + 'mm';
        kop.style.borderBottom = borders[v('d[kop_border]')] || borders.double;
        kop.style.marginBottom = v('d[space_after_kop]') + 'mm';
        logo.style.display = c('d[show_logo]') ? '' : 'none';
        l2.textContent = v('d[kop_dept]');
        meta.style.display = c('d[show_meta]') ? '' : 'none';
        meta.style.marginBottom = v('d[space_paragraph]') + 'mm';
        var ps = sheet.querySelectorAll('.pv-p');
        for (var i = 0; i < ps.length; i++) { ps[i].style.marginBottom = v('d[space_paragraph]') + 'mm'; }
        signTitle.textContent = v('d[sign_title]') + ',';
        paraf.style.display = c('d[show_paraf]') ? '' : 'none';
        nip.style.display = c('d[show_nip]') ? '' : 'none';
    }

    form.addEventListener('input', refresh);
    refresh();
})();
</script>

<!-- ================================================================ -->
<!-- DAFTAR TEMPLATE ISI SURAT                                          -->
<!-- ================================================================ -->
<div class="card" style="margin-bottom:1.4rem;margin-top:1.4rem;">
    <h3 class="card-title">Daftar Template Isi Surat</h3>
    <table class="data-table">
        <thead><tr><th>Kode</th><th>Nama</th><th>Aktif</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= e($row['code']); ?></td>
                    <td><?= e($row['name']); ?></td>
                    <td><?= (int) $row['is_active'] === 1 ? 'Ya' : 'Tidak'; ?></td>
                    <td>
                        <form method="POST" action="<?= admin_url('/template-surat/' . $row['id'] . '/hapus'); ?>" data-confirm="Hapus template ini?">
                            <?= csrf_field(); ?>
                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php foreach ($rows as $row): ?>
<div class="form-card" style="margin-bottom:1.4rem;">
    <h3 class="card-title">Ubah: <?= e($row['name']); ?></h3>
    <form method="POST" action="<?= admin_url('/template-surat/' . $row['id']); ?>">
        <?= csrf_field(); ?>
        <label for="name<?= (int) $row['id']; ?>">Nama Template</label>
        <input id="name<?= (int) $row['id']; ?>" name="name" value="<?= e($row['name']); ?>">
        <label for="subject<?= (int) $row['id']; ?>">Perihal Bawaan</label>
        <input id="subject<?= (int) $row['id']; ?>" name="subject" value="<?= e($row['subject'] ?? ''); ?>">
        <label for="content<?= (int) $row['id']; ?>">Isi Template (placeholder)</label>
        <textarea id="content<?= (int) $row['id']; ?>" name="content" rows="10"><?= e($row['content']); ?></textarea>
        <div class="checkbox-row">
            <input type="checkbox" id="is_active<?= (int) $row['id']; ?>" name="is_active" <?= (int) $row['is_active'] === 1 ? 'checked' : ''; ?>>
            <label for="is_active<?= (int) $row['id']; ?>">Aktif digunakan</label>
        </div>
        <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Simpan Template</button></div>
    </form>
</div>
<?php endforeach; ?>

<div class="form-card">
    <h3 class="card-title">Tambah Template Baru</h3>
    <form method="POST" action="<?= admin_url('/template-surat'); ?>">
        <?= csrf_field(); ?>
        <div class="form-grid">
            <div><label for="code">Kode *</label><input id="code" name="code" required></div>
            <div><label for="name">Nama *</label><input id="name" name="name" required></div>
            <div class="form-full"><label for="content">Isi Template *</label><textarea id="content" name="content" rows="8" required></textarea></div>
        </div>
        <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Simpan</button></div>
    </form>
</div>