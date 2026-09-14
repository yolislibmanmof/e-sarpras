<section class="hero" id="hero">
    <div class="hero-inner"></div>
    <div class="hero-aurora aurora-1" data-depth="26"></div>
    <div class="hero-aurora aurora-2" data-depth="40"></div>

    <div class="container hero-flex">
        <div class="hero-text">
            <span class="hero-badge">Sistem Informasi Sarpras Kampus</span>
            <h1>Pengelolaan Sarpras yang
                <span class="rot-wrap"><span class="rot-word" id="rotWord">Transparan</span></span>
            </h1>
            <p data-words>Platform terpadu untuk inventarisasi aset, penanganan laporan kerusakan, peminjaman barang dan ruangan, pemeliharaan berkala, hingga pelaporan akreditasi.</p>
            <p class="typed-line"><span id="typed"></span><span class="caret"></span></p>
            <div class="hero-actions">
                <a href="<?= base_url('/lapor-kerusakan'); ?>" class="btn btn-primary">Lapor Kerusakan</a>
                <a href="<?= base_url('/peminjaman'); ?>" class="btn btn-outline">Peminjaman</a>
            </div>
        </div>

        <div class="hero-visual" data-depth="14">
            <div class="orbit"></div>
            <div class="glass-panel">
                <div class="glass-head">
                    <span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>
                    <em>dashboard sarpras</em>
                </div>
                <div class="glass-row"><span>Tiket Minggu Ini</span><b>17</b></div>
                <div class="glass-spark">
                    <i style="--h:35%"></i><i style="--h:55%"></i><i style="--h:42%"></i><i style="--h:70%"></i><i style="--h:58%"></i><i style="--h:86%"></i><i style="--h:64%"></i>
                </div>
                <div class="glass-row"><span>Ruang Terpakai Hari Ini</span><b>82%</b></div>
                <div class="glass-bar"><span style="--w:82%"></span></div>
                <div class="glass-row"><span>APAR Siap Pakai</span><b>96%</b></div>
                <div class="glass-bar"><span style="--w:96%"></span></div>
            </div>
            <div class="glass-float chip-1">&#10003; K3L Aman</div>
            <div class="glass-float chip-2">&#9733; Akreditasi Siap</div>
        </div>
    </div>

    <a class="hero-scroll" href="#layanan" aria-label="Gulir ke layanan"><span></span></a>
</section>

<section class="section" style="padding:2.6rem 0;">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card"><span class="stat-value"><?= (int) $stats['buildings']; ?></span><span class="stat-label">Gedung</span></div>
            <div class="stat-card"><span class="stat-value"><?= (int) $stats['rooms']; ?></span><span class="stat-label">Ruangan</span></div>
            <div class="stat-card"><span class="stat-value"><?= (int) $stats['assets']; ?></span><span class="stat-label">Aset Tercatat</span></div>
            <div class="stat-card"><span class="stat-value"><?= (int) $stats['tickets_open']; ?></span><span class="stat-label">Laporan Menunggu</span></div>
        </div>
    </div>
</section>

<div class="big-marquee" aria-hidden="true">
    <div class="big-track">
        <?php for ($r = 0; $r < 2; $r++): ?>
            <span>SARPRAS</span><i>&#10022;</i><span>FASILITAS</span><i>&#10022;</i><span>AKREDITASI</span><i>&#10022;</i><span>K3L</span><i>&#10022;</i><span>INVENTARIS</span><i>&#10022;</i>
        <?php endfor; ?>
    </div>
</div>

<!-- GALERI KARTU: kini menggantikan posisi pita unit, dengan kartu kecil -->
<section class="hscroll-section" id="hscrollSection">
    <div class="hscroll">
        <div class="hscroll-track" id="hscrollTrack">
            <div class="h-tile g1"><div class="h-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></svg></div><h3>Ruang Kelas Cerdas</h3><p>Smart classroom untuk pembelajaran bauran.</p></div>
            <div class="h-tile g2"><div class="h-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 2v7.5L4.5 19a2 2 0 0 0 1.8 3h11.4a2 2 0 0 0 1.8-3L14 9.5V2"/><path d="M8 2h8"/></svg></div><h3>Laboratorium Terpadu</h3><p>Fasilitas praktik sesuai rumpun keilmuan.</p></div>
            <div class="h-tile g3"><div class="h-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/></svg></div><h3>Auditorium</h3><p>Pusat kegiatan besar kampus.</p></div>
            <div class="h-tile g4"><div class="h-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></div><h3>Perpustakaan Digital</h3><p>Koleksi fisik dan e-journal internasional.</p></div>
            <div class="h-tile g5"><div class="h-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div><h3>Sport Center</h3><p>Sarana olahraga dan minat bakat.</p></div>
            <div class="h-tile g6"><div class="h-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22a10 10 0 1 1 10-10c0 2-2 3-4 3h-2a3 3 0 0 0-3 3c0 2 1 4-1 4z"/></svg></div><h3>Taman &amp; Ruang Terbuka</h3><p>Ruang interaksi yang nyaman dan hijau.</p></div>
        </div>
    </div>
</section>

<section class="section section-alt" id="layanan">
    <div class="container">
        <h2 class="section-title" data-scramble>Layanan Unggulan</h2>
        <p class="section-lead">Satu pintu untuk seluruh kebutuhan sarana dan prasarana — cepat, terlacak, dan terdokumentasi.</p>

        <div class="bento-grid">
            <a class="card bento bento-lg" href="<?= base_url('/lapor-kerusakan'); ?>">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.3 3.8 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.8a2 2 0 0 0-3.4 0z"/></svg>
                </div>
                <h3>Lapor Kerusakan</h3>
                <p>Foto kerusakan, kirim, dan pantau status penanganan secara real-time hingga tuntas.</p>
                <div class="mini-bars">
                    <div class="mini-row"><span>Menunggu</span><div class="mini-bar"><i style="--w:22%"></i></div></div>
                    <div class="mini-row"><span>Diproses</span><div class="mini-bar"><i style="--w:46%"></i></div></div>
                    <div class="mini-row"><span>Selesai</span><div class="mini-bar"><i style="--w:88%"></i></div></div>
                </div>
                <span class="service-link">Buat Laporan</span>
            </a>

            <a class="card bento" href="<?= base_url('/peminjaman/barang'); ?>">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
                </div>
                <h3>Peminjaman Barang</h3>
                <p>Proyektor, sound system, dan inventaris lain dengan persetujuan jelas.</p>
            </a>

            <a class="card bento" href="<?= base_url('/peminjaman/ruangan'); ?>">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                </div>
                <h3>Peminjaman Ruangan</h3>
                <p>Aula, ruang kelas, dan laboratorium bersama tanpa jadwal bentrok.</p>
            </a>

            <a class="card bento bento-wide" href="<?= base_url('/survei'); ?>">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M8 17v-6"/><path d="M13 17V7"/><path d="M18 17v-4"/></svg>
                </div>
                <h3>Survei &amp; Akreditasi</h3>
                <p>Kepuasan pengguna dan utilitas ruangan terekam rapi untuk borang.</p>
                <span class="stars"><b>&#9733;&#9733;&#9733;&#9733;&#9733;</b> 4.8 / 5 kepuasan sivitas</span>
            </a>

            <a class="card bento" href="<?= base_url('/gedung'); ?>">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M12 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M12 10h.01"/><path d="M16 10h.01"/></svg>
                </div>
                <h3>Informasi Gedung</h3>
                <p>Profil gedung, ruangan, dan aksesibilitas dalam satu peta data.</p>
            </a>

            <a class="card bento" href="<?= base_url('/permintaan-barang'); ?>">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                </div>
                <h3>Permintaan ATK</h3>
                <p>Unit dan dosen mengajukan ATK/elektronik langsung dari unit masing-masing.</p>
            </a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="section-title" data-scramble>Alur Layanan</h2>
        <div class="vtimeline">
            <span class="vt-line"><i id="vtProgress"></i></span>

            <div class="vt-item">
                <span class="vt-dot">1</span>
                <div class="card vt-card">
                    <h3>Pengajuan</h3>
                    <p>Sivitas akademika mengajukan laporan, peminjaman, atau permintaan melalui formulir daring — lengkap dengan foto dan kode pelacakan.</p>
                </div>
            </div>
            <div class="vt-item">
                <span class="vt-dot">2</span>
                <div class="card vt-card">
                    <h3>Verifikasi</h3>
                    <p>Tim Sarpras memverifikasi urgensi dan meneruskan ke pimpinan bila membutuhkan persetujuan anggaran.</p>
                </div>
            </div>
            <div class="vt-item">
                <span class="vt-dot">3</span>
                <div class="card vt-card">
                    <h3>Penanganan</h3>
                    <p>Teknisi internal atau vendor eksternal mengeksekusi perbaikan maupun penyerahan barang dengan status yang terus diperbarui.</p>
                </div>
            </div>
            <div class="vt-item">
                <span class="vt-dot">4</span>
                <div class="card vt-card">
                    <h3>Selesai &amp; Pelaporan</h3>
                    <p>Aset dicatat, kondisi diperbarui, dan seluruh data tersimpan rapi untuk laporan serta kebutuhan akreditasi.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <h2 class="section-title" data-scramble>Kata Mereka</h2>
        <div class="testi">
            <div class="testi-track" id="testiTrack">
                <div class="testi-slide">
                    <p class="testi-quote">Laporan proyektor rusak di ruang kuliah ditangani dalam satu hari. Statusnya bisa saya pantau sendiri sampai selesai.</p>
                    <p class="testi-name">Andini P.</p>
                    <p class="testi-role">Mahasiswa Fakultas Teknik</p>
                </div>
                <div class="testi-slide">
                    <p class="testi-quote">Peminjaman auditorium untuk seminar kini tanpa bolak-balik berkas. Semua terlacak dan ada bukti persetujuannya.</p>
                    <p class="testi-name">Dr. R. Hartono</p>
                    <p class="testi-role">Dosen Fakultas Ekonomi &amp; Bisnis</p>
                </div>
                <div class="testi-slide">
                    <p class="testi-quote">Data utilitas ruangan dan K3L langsung siap saat asesmen. Pekerjaan penjaminan mutu jauh lebih ringan.</p>
                    <p class="testi-name">Tim SPMI</p>
                    <p class="testi-role">Staf Penjaminan Mutu</p>
                </div>
            </div>
            <div class="testi-dots" id="testiDots"></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container faq-grid">
        <div>
            <h2 class="section-title" data-scramble>Tanya Jawab</h2>
            <p class="section-lead">Masih ada pertanyaan? Tim Sarpras siap membantu pada jam layanan.</p>
            <a class="btn btn-primary" href="<?= base_url('/lapor-kerusakan'); ?>">Hubungi Kami</a>
        </div>
        <div class="faq-list">
            <div class="faq-item">
                <button type="button" class="faq-q">Bagaimana cara melaporkan kerusakan fasilitas?<span class="faq-x">+</span></button>
                <div class="faq-a"><p>Buka menu Lapor Kerusakan, isi formulir, unggah foto bukti, lalu kirim. Anda akan menerima kode tiket untuk memantau status penanganan.</p></div>
            </div>
            <div class="faq-item">
                <button type="button" class="faq-q">Berapa lama waktu penanganan laporan?<span class="faq-x">+</span></button>
                <div class="faq-a"><p>Laporan diverifikasi maksimal 1x24 jam kerja. Penanganan disesuaikan dengan urgensi; laporan darurat diprioritaskan.</p></div>
            </div>
            <div class="faq-item">
                <button type="button" class="faq-q">Siapa yang dapat meminjam barang dan ruangan?<span class="faq-x">+</span></button>
                <div class="faq-a"><p>Mahasiswa, dosen, tendik, dan unit/prodi dapat mengajukan peminjaman melalui sistem dengan persetujuan Bagian Sarpras.</p></div>
            </div>
            <div class="faq-item">
                <button type="button" class="faq-q">Apakah layanan ini berbayar?<span class="faq-x">+</span></button>
                <div class="faq-a"><p>Tidak. Seluruh layanan Sarpras merupakan bagian dari fasilitas kampus untuk mendukung tri dharma perguruan tinggi.</p></div>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container komitmen-grid">
        <div>
            <h2 class="section-title">Komitmen Mutu, K3L, dan Inklusi</h2>
            <p class="section-lead" style="margin-bottom:0;">Kami mengelola sarana dan prasarana sesuai standar penjaminan mutu pendidikan tinggi dan prinsip kampus yang ramah bagi seluruh sivitas akademika.</p>
            <ul class="check-list">
                <li>Fasilitas ramah disabilitas: ramp, toilet khusus, lift, dan jalur pemandu.</li>
                <li>Standar K3L: APAR terawat, rute evakuasi jelas, dan pengelolaan limbah B3.</li>
                <li>Pemeliharaan preventif terjadwal untuk seluruh utilitas gedung.</li>
                <li>Data sarana-prasarana siap pakai untuk borang akreditasi BAN-PT/LAM.</li>
            </ul>
        </div>
        <div class="card komitmen-card">
            <h3>Sarpras dalam Angka</h3>
            <p>Didata dan diperbarui secara berkelanjutan oleh Bagian Sarana dan Prasarana.</p>
            <div class="komitmen-stat">
                <div><b><?= (int) $stats['assets']; ?></b><span>Aset Tercatat</span></div>
                <div><b><?= (int) $stats['rooms']; ?></b><span>Ruangan Terdata</span></div>
            </div>
        </div>
    </div>
</section>

<section class="cta-band">
    <div class="container cta-inner">
        <h2>Butuh Bantuan Sarpras?</h2>
        <p>Sampaikan laporan atau permintaan Anda sekarang. Tim kami siap menindaklanjuti dengan cepat dan terukur.</p>
        <div class="hero-actions" style="justify-content:center;">
            <a href="<?= base_url('/lapor-kerusakan'); ?>" class="btn btn-primary">Lapor Sekarang</a>
            <a href="<?= base_url('/lacak-laporan'); ?>" class="btn btn-outline">Lacak Laporan</a>
        </div>
    </div>
</section>

<script src="<?= asset_url('js/home.js'); ?>"></script>