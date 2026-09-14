<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Security\Auth;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function index(): void
    {
        $data = (new DashboardService())->overview();

        $data['title'] = 'Dashboard';
        $data['user']  = Auth::user();
        $data['roles'] = Auth::roles();

        $this->view('admin/pages/dashboard', $data, 'admin/layouts/admin');
    }
}