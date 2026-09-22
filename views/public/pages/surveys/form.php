<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$pctRequired = $totalCount > 0 ? (int) round($requiredCount / $totalCount * 100) : 0;
$dbS = \App\Core\Database::instance();
$avgRow = $dbS->selectOne(
    "SELECT AVG(sa.rating_value) AS avg_rating
     FROM survey_answers sa
     JOIN survey_questions sq ON sq.id = sa.survey_question_id
     WHERE sq.survey_id = ? AND sa.rating_value IS NOT NULL",
    [$survey['id']]
);
$avgRating = $avgRow !== null ? (float) ($avgRow['avg_rating'] ?? 0) : 0.0;
$satPct = $avgRating > 0 ? min(100, round($avgRating / 5 * 100)) : 0;
$qIds = array_map(static fn ($q) => (int) $q['id'], $questions);
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/survei'); ?>">Survei</a> &rarr; <?= e($survey['title']); ?></span>
        <h1><?= e($survey['title']); ?></h1>
        <p><?= e($survey['description'] ?? ''); ?></p>
        <p style="margin-top:.8rem;"><span class="badge badge-info">&#9202; Estimasi waktu &plusmn;2 menit</span> <span class="badge badge-success"><?= number_format($responseCount); ?> respons masuk</span></p>
    </div>
</section>

<section class="section page-body">
    <div class="container split-grid">
        <div>
            <!-- Bilah progres terjawab -->
            <div class="card shine" style="padding:1rem 1.2rem;margin-bottom:1.2rem;">
                <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:.3rem;">
                    <strong style="color:var(--primary-dark);">Progres Pengisian</strong>
                    <span id="svLbl" style="color:var(--muted);">0 / <?= (int) $totalCount; ?> terjawab</span>
                </div>
                <div class="sv-progresswrap"><div class="sv-progressbar" id="svBar"></div></div>
            </div>

            <div class="public-form-card shine">
                <form method="POST" action="<?= base_url('/survei/' . $survey['slug']); ?>" id="surveyForm">
                    <?= csrf_field(); ?>
                    <div class="form-grid">
                        <div><label for="respondent_name">Nama (opsional)</label><input id="respondent_name" name="respondent_name"></div>
                        <div>
                            <label for="respondent_type">Status</label>
                            <select id="respondent_type" name="respondent_type">
                                <option value="">Anonim</option>
                                <?php foreach (['Mahasiswa', 'Dosen', 'Tendik'] as $type): ?>
                                    <option value="<?= e($type); ?>"><?= e($type); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <?php foreach ($questions as $qi => $question): ?>
                        <div class="card shine sv-q" style="padding:1.2rem;margin-top:1rem;">
                            <div style="display:flex;gap:.8rem;align-items:flex-start;">
                                <span class="sv-num"><?= (int) $qi + 1; ?></span>
                                <div style="flex:1;">
                                    <label><?= e($question['question']); ?> <?= (int) $question['is_required'] === 1 ? ' *' : ''; ?></label>
                                    <?php if ($question['question_type'] === 'rating'): ?>
                                        <div class="rating-row">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <label class="rating-option">
                                                    <input type="radio" name="answers[<?= (int) $question['id']; ?>]" value="<?= $i; ?>" <?= (int) $question['is_required'] === 1 ? 'required' : ''; ?>>
                                                    <span><?= $i; ?></span>
                                                </label>
                                            <?php endfor; ?>
                                        </div>
                                        <small style="color:var(--muted);">1 = sangat kurang &nbsp;&rarr;&nbsp; 5 = sangat baik</small>
                                    <?php else: ?>
                                        <textarea name="answers[<?= (int) $question['id']; ?>]" rows="3" <?= (int) $question['is_required'] === 1 ? 'required' : ''; ?>></textarea>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="form-actions" style="margin-top:1.4rem;">
                        <button type="submit" class="btn btn-primary">Kirim Tanggapan</button>
                        <a class="btn btn-outline" href="<?= base_url('/survei'); ?>">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        <aside class="side-panel">
            <div class="card info-card shine" style="text-align:center;">
                <h4>Kepuasan Survei Ini</h4>
                <div class="ring" style="--off:<?= (int) (264 * (1 - $satPct / 100)); ?>;margin:0.6rem auto 0;">
                    <svg width="92" height="92"><circle class="bgc" cx="46" cy="46" r="42"/><circle class="fgc" cx="46" cy="46" r="42"/></svg>
                    <div class="val"><?= $avgRating > 0 ? number_format($avgRating, 1) : '-'; ?><small>dari 5.0</small></div>
                </div>
                <p style="margin-top:.7rem;font-size:.8rem;"><?= number_format($responseCount); ?> responden sebelumnya</p>
            </div>

            <div class="card info-card shine">
                <h4>Ringkasan Survei</h4>
                <ul class="mini-steps">
                    <li><b>&#9632;</b> Total pertanyaan: <strong><?= (int) $totalCount; ?></strong></li>
                    <li><b>&#9632;</b> Pertanyaan wajib: <strong><?= (int) $requiredCount; ?></strong></li>
                    <li><b>&#9632;</b> Respons masuk: <strong><?= (int) $responseCount; ?></strong></li>
                </ul>
                <div class="mini-row" style="margin-top:0.8rem;">
                    <span>Wajib</span>
                    <div class="mini-bar"><i style="--w:<?= $pctRequired; ?>%"></i></div>
                </div>
            </div>

            <div class="card info-card">
                <h4>Mengapa Suara Anda Penting?</h4>
                <ul class="mini-steps">
                    <li><b>&#10003;</b> Hasil survei dianalisis tiap semester.</li>
                    <li><b>&#10003;</b> Keluhan ditindaklanjuti secara terukur.</li>
                    <li><b>&#10003;</b> Data mendukung perbaikan dan akreditasi.</li>
                </ul>
            </div>
        </aside>
    </div>
</section>

<style>
.sv-num{width:26px;height:26px;border-radius:9px;background:linear-gradient(135deg,var(--primary),var(--accent-2));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;flex-shrink:0}
.sv-progresswrap{background:var(--bg);border-radius:999px;height:10px;overflow:hidden}
.sv-progressbar{height:100%;width:0;background:linear-gradient(90deg,var(--primary),var(--accent-2));border-radius:999px;transition:width .4s ease}
.rating-option span{transition:transform .2s ease,background .2s ease,color .2s ease}
.rating-option span:hover{transform:scale(1.15);background:linear-gradient(135deg,var(--primary),var(--accent-2));color:#fff}
html[data-theme="dark"] .sv-progresswrap{background:var(--border)}
</style>

<script>
(function () {
    var form = document.getElementById('surveyForm');
    if (!form) { return; }
    var total = <?= (int) $totalCount; ?>;
    var ids = <?= json_encode($qIds); ?>;
    var bar = document.getElementById('svBar');
    var lbl = document.getElementById('svLbl');

    function countDone() {
        var done = 0;
        ids.forEach(function (id) {
            var radios = form.querySelectorAll('input[name="answers[' + id + ']"]');
            if (radios.length) {
                for (var i = 0; i < radios.length; i++) { if (radios[i].checked) { done++; break; } }
            } else {
                var ta = form.querySelector('textarea[name="answers[' + id + ']"]');
                if (ta && ta.value.trim() !== '') { done++; }
            }
        });
        return done;
    }

    function refresh() {
        var d = countDone();
        var pct = total > 0 ? Math.round(d / total * 100) : 0;
        bar.style.width = pct + '%';
        lbl.textContent = d + ' / ' + total + ' terjawab';
    }

    form.addEventListener('change', refresh);
    form.addEventListener('input', refresh);
    refresh();
})();
</script>