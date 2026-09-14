<?php

declare(strict_types=1);

namespace App\Core;

use App\Security\Auth;

abstract class Controller
{
    protected function view(string $view, array $data = [], ?string $layout = null): void
    {
        View::render($view, $data, $layout);
    }

    protected function adminView(string $view, array $data = []): void
    {
        $data['user']  = $data['user'] ?? Auth::user();
        $data['roles'] = $data['roles'] ?? Auth::roles();
        $this->view($view, $data, 'admin/layouts/admin');
    }

    protected function json($data, int $code = 200): void
    {
        Response::json($data, $code);
    }

    protected function redirect(string $url): void
    {
        Response::redirect($url);
    }

    protected function validate(array $data, array $rules): array
    {
        $validator = new Validator();
        return $validator->validate($data, $rules);
    }

    protected function db(): Database
    {
        return Database::instance();
    }
}