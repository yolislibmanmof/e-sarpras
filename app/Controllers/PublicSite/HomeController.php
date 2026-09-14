<?php

declare(strict_types=1);

namespace App\Controllers\PublicSite;

use App\Core\Controller;
use App\Core\Database;

class HomeController extends Controller
{
    public function index(): void
    {
        $stats = [
            'buildings'    => 0,
            'rooms'        => 0,
            'assets'       => 0,
            'tickets_open' => 0,
        ];

        try {
            $db = Database::instance();
            $stats['buildings']    = $db->count('buildings');
            $stats['rooms']        = $db->count('rooms');
            $stats['assets']       = $db->count('assets');
            $stats['tickets_open'] = $db->count('tickets', ['status' => 'Menunggu Verifikasi']);
        } catch (\Throwable $e) {
            // Database belum siap; statistik dibiarkan nol.
        }

        $this->view('public/pages/home', [
            'title' => 'Beranda',
            'stats' => $stats,
        ], 'public/layouts/main');
    }
}