<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class SurveyController extends Controller
{
    public function index(): void
    {
        $rows = $this->db()->select(
            "SELECT s.*, (SELECT COUNT(*) FROM survey_responses sr WHERE sr.survey_id = s.id) AS response_count
             FROM surveys s ORDER BY s.id DESC"
        );

        $this->adminView('admin/pages/surveys/index', [
            'title' => 'Survei Kepuasan',
            'rows'  => $rows,
        ]);
    }

    public function show(string $id): void
    {
        $survey = $this->db()->selectOne('SELECT * FROM surveys WHERE id = ?', [$id]);
        if ($survey === null) {
            Session::flash('error', 'Survei tidak ditemukan.');
            $this->redirect(admin_url('/survei'));
        }

        $questions = $this->db()->select('SELECT * FROM survey_questions WHERE survey_id = ? ORDER BY sort_order, id', [$id]);

        $recap = [];
        foreach ($questions as $question) {
            if ($question['question_type'] === 'rating') {
                $row = $this->db()->selectOne(
                    'SELECT AVG(rating_value) AS avg, COUNT(*) AS total FROM survey_answers WHERE survey_question_id = ?',
                    [$question['id']]
                );
                $recap[$question['id']] = [
                    'type'  => 'rating',
                    'avg'   => round((float) ($row['avg'] ?? 0), 2),
                    'total' => (int) ($row['total'] ?? 0),
                ];
            } else {
                $recap[$question['id']] = [
                    'type'    => 'text',
                    'answers' => $this->db()->select(
                        "SELECT sa.answer_text, sr.respondent_name, sa.created_at
                         FROM survey_answers sa
                         JOIN survey_responses sr ON sr.id = sa.survey_response_id
                         WHERE sa.survey_question_id = ? AND sa.answer_text IS NOT NULL
                         ORDER BY sa.id DESC LIMIT 10",
                        [$question['id']]
                    ),
                ];
            }
        }

        $this->adminView('admin/pages/surveys/show', [
            'title'     => 'Rekap Survei',
            'survey'    => $survey,
            'questions' => $questions,
            'recap'     => $recap,
        ]);
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, ['title' => 'required|max:255']);

        if ($errors !== []) {
            Session::flash('error', 'Judul survei wajib diisi.');
            $this->redirect(admin_url('/survei'));
        }

        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $data['title'])) . '-' . time();

        $id = $this->db()->insert('surveys', [
            'title'           => $data['title'],
            'slug'            => $slug,
            'description'     => $data['description'] ?? null,
            'target_audience' => $data['target_audience'] ?? null,
            'is_active'       => isset($data['is_active']) ? 1 : 0,
            'created_by'      => auth_id(),
        ]);

        AuditLogger::log('survey.create', 'survey', $id);
        Session::flash('success', 'Survei berhasil dibuat.');
        $this->redirect(admin_url('/survei/' . $id));
    }

    public function addQuestion(string $id): void
    {
        $survey = $this->db()->selectOne('SELECT * FROM surveys WHERE id = ?', [$id]);
        if ($survey === null) {
            Session::flash('error', 'Survei tidak ditemukan.');
            $this->redirect(admin_url('/survei'));
        }

        $data = Request::all();
        $errors = $this->validate($data, ['question' => 'required']);

        if ($errors !== []) {
            Session::flash('error', 'Isi pertanyaan wajib diisi.');
            $this->redirect(admin_url('/survei/' . $id));
        }

        $this->db()->insert('survey_questions', [
            'survey_id'     => (int) $id,
            'question'      => $data['question'],
            'question_type' => ($data['question_type'] ?? 'rating') === 'text' ? 'text' : 'rating',
            'sort_order'    => (int) ($data['sort_order'] ?? 0),
            'is_required'   => isset($data['is_required']) ? 1 : 0,
        ]);

        AuditLogger::log('survey.question.add', 'survey', $id);
        Session::flash('success', 'Pertanyaan berhasil ditambahkan.');
        $this->redirect(admin_url('/survei/' . $id));
    }

    public function toggle(string $id): void
    {
        $survey = $this->db()->selectOne('SELECT * FROM surveys WHERE id = ?', [$id]);
        if ($survey === null) {
            Session::flash('error', 'Survei tidak ditemukan.');
            $this->redirect(admin_url('/survei'));
        }

        $this->db()->update('surveys', ['is_active' => (int) $survey['is_active'] === 1 ? 0 : 1], ['id' => $id]);
        AuditLogger::log('survey.toggle', 'survey', $id);
        Session::flash('success', 'Status survei diperbarui.');
        $this->redirect(admin_url('/survei/' . $id));
    }

    public function destroy(string $id): void
    {
        $this->db()->delete('survey_questions', ['survey_id' => $id]);
        $this->db()->delete('surveys', ['id' => $id]);
        AuditLogger::log('survey.delete', 'survey', $id);
        Session::flash('success', 'Survei berhasil dihapus.');
        $this->redirect(admin_url('/survei'));
    }

    public function deleteQuestion(string $id): void
    {
        $question = $this->db()->selectOne('SELECT * FROM survey_questions WHERE id = ?', [$id]);
        if ($question === null) {
            Session::flash('error', 'Pertanyaan tidak ditemukan.');
            $this->redirect(admin_url('/survei'));
        }

        $this->db()->delete('survey_answers', ['survey_question_id' => $id]);
        $this->db()->delete('survey_questions', ['id' => $id]);
        AuditLogger::log('survey.question.delete', 'survey', $question['survey_id']);
        Session::flash('success', 'Pertanyaan berhasil dihapus.');
        $this->redirect(admin_url('/survei/' . $question['survey_id']));
    }
}