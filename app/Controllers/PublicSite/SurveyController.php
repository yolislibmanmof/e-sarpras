<?php

declare(strict_types=1);

namespace App\Controllers\PublicSite;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class SurveyController extends Controller
{
    public function index(): void
    {
        $surveys = $this->db()->select(
            "SELECT s.*, (SELECT COUNT(*) FROM survey_responses sr WHERE sr.survey_id = s.id) AS response_count
             FROM surveys s WHERE s.is_active = 1 ORDER BY s.id DESC"
        );

        $this->view('public/pages/surveys/index', [
            'title'   => 'Survei Kepuasan',
            'surveys' => $surveys,
        ], 'public/layouts/main');
    }

    public function show(string $slug): void
    {
        $survey = $this->db()->selectOne('SELECT * FROM surveys WHERE slug = ? AND is_active = 1', [$slug]);
        if ($survey === null) {
            Session::flash('error', 'Survei tidak ditemukan atau sudah ditutup.');
            $this->redirect(base_url('/survei'));
        }

        $questions = $this->db()->select('SELECT * FROM survey_questions WHERE survey_id = ? ORDER BY sort_order ASC, id ASC', [$survey['id']]);
        $required = 0;
        foreach ($questions as $q) { if ((int) $q['is_required'] === 1) { $required++; } }

        $this->view('public/pages/surveys/form', [
            'title'      => $survey['title'],
            'survey'     => $survey,
            'questions'  => $questions,
            'totalCount' => count($questions),
            'requiredCount' => $required,
            'responseCount' => (int) ($this->db()->selectOne('SELECT COUNT(*) AS total FROM survey_responses WHERE survey_id = ?', [$survey['id']])['total'] ?? 0),
        ], 'public/layouts/main');
    }

    public function submit(string $slug): void
    {
        $survey = $this->db()->selectOne('SELECT * FROM surveys WHERE slug = ? AND is_active = 1', [$slug]);
        if ($survey === null) {
            Session::flash('error', 'Survei tidak ditemukan atau sudah ditutup.');
            $this->redirect(base_url('/survei'));
        }

        $questions = $this->db()->select('SELECT * FROM survey_questions WHERE survey_id = ? ORDER BY sort_order ASC, id ASC', [$survey['id']]);
        $answers = Request::input('answers', []);
        if (!is_array($answers)) { $answers = []; }

        foreach ($questions as $q) {
            if ((int) $q['is_required'] === 1) {
                $value = $answers[$q['id']] ?? '';
                if (trim((string) $value) === '') {
                    Session::flash('error', 'Mohon lengkapi seluruh pertanyaan wajib.');
                    $this->redirect(base_url('/survei/' . $slug));
                }
            }
        }

        $responseId = $this->db()->insert('survey_responses', [
            'survey_id'       => (int) $survey['id'],
            'respondent_type' => Request::input('respondent_type', '') ?: null,
            'respondent_name' => Request::input('respondent_name', '') ?: null,
            'status'          => 'Selesai',
        ]);

        foreach ($questions as $q) {
            $value = $answers[$q['id']] ?? null;
            if ($value === null || trim((string) $value) === '') { continue; }
            $this->db()->insert('survey_answers', [
                'survey_response_id' => $responseId,
                'survey_question_id' => (int) $q['id'],
                'answer_text'        => $q['question_type'] === 'rating' ? null : (string) $value,
                'rating_value'       => $q['question_type'] === 'rating' ? (int) $value : null,
            ]);
        }

        AuditLogger::log('survey.response', 'survey', $responseId, null, ['survey' => $slug]);
        Session::flash('success', 'Terima kasih! Tanggapan Anda telah tercatat.');
        $this->redirect(base_url('/survei'));
    }
}