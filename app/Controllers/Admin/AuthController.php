<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;
use App\Security\Auth;
use App\Security\RateLimiter;

class AuthController extends Controller
{
    public function root(): void
    {
        $this->redirect(Auth::check() ? admin_url('/dashboard') : admin_url('/login'));
    }

    public function showLogin(): void
    {
        $this->view('admin/auth/login', [
            'title' => 'Login Admin',
        ], 'admin/layouts/auth');
    }

    public function login(): void
    {
        $errors = $this->validate(Request::all(), [
            'username' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($errors !== []) {
            $messages = [];
            foreach ($errors as $list) {
                foreach ($list as $message) {
                    $messages[] = $message;
                }
            }
            Session::flash('error', implode(' ', $messages));
            Session::flash('old_username', Request::input('username'));
            $this->redirect(admin_url('/login'));
        }

        $identifier = Request::input('username');
        $password   = Request::input('password');
        $rateKey    = 'login:' . Request::ip();

        if (Auth::attempt($identifier, $password)) {
            RateLimiter::reset($rateKey);
            AuditLogger::log('login.success', 'auth', Auth::id());
            Session::flash('success', 'Selamat datang, ' . (Auth::user()['full_name'] ?? '') . '.');
            $this->redirect(admin_url('/dashboard'));
        }

        $max     = (int) config('security.login_max_attempts', 5);
        $minutes = (int) config('security.login_lockout_minutes', 15);
        RateLimiter::hit($rateKey, $max, $minutes);
        AuditLogger::log('login.failed', 'auth', null, null, ['identifier' => $identifier]);

        Session::flash('error', 'Username atau password salah.');
        Session::flash('old_username', $identifier);
        $this->redirect(admin_url('/login'));
    }

    public function logout(): void
    {
        AuditLogger::log('login.logout', 'auth', Auth::id());
        Auth::logout();
        Session::flash('success', 'Anda telah keluar dari sistem.');
        $this->redirect(admin_url('/login'));
    }
}