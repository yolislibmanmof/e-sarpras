<?php

use App\Controllers\PublicSite\BorrowController;
use App\Controllers\PublicSite\BuildingInfoController;
use App\Controllers\PublicSite\HomeController;
use App\Controllers\PublicSite\ItemRequestController;
use App\Controllers\PublicSite\MediaController;
use App\Controllers\PublicSite\SurveyController;
use App\Controllers\PublicSite\TicketController;

$router->get('/', [HomeController::class, 'index']);

// Berkas media (foto gedung/ruangan/logo) dari storage
$router->get('/media/{a}/{b}', [MediaController::class, 'show']);

// Informasi Gedung & Ruang
$router->get('/gedung', [BuildingInfoController::class, 'index']);
$router->get('/gedung/{id}', [BuildingInfoController::class, 'show']);
$router->get('/ruangan/{id}', [BuildingInfoController::class, 'room']);

// Tiket Kerusakan
$router->get('/lapor-kerusakan', [TicketController::class, 'create']);
$router->post('/lapor-kerusakan', [TicketController::class, 'store'], ['rate:ticket', 'csrf']);
$router->get('/lacak-laporan', [TicketController::class, 'track']);

// Peminjaman
$router->get('/peminjaman', [BorrowController::class, 'landing']);
$router->get('/peminjaman/barang', [BorrowController::class, 'borrowForm']);
$router->post('/peminjaman/barang', [BorrowController::class, 'storeBorrow'], ['rate:ticket', 'csrf']);
$router->get('/peminjaman/ruangan', [BorrowController::class, 'bookingForm']);
$router->post('/peminjaman/ruangan', [BorrowController::class, 'storeBooking'], ['rate:ticket', 'csrf']);

// Permintaan Barang
$router->get('/permintaan-barang', [ItemRequestController::class, 'form']);
$router->post('/permintaan-barang', [ItemRequestController::class, 'store'], ['rate:ticket', 'csrf']);

// Survei Kepuasan
$router->get('/survei', [SurveyController::class, 'index']);
$router->get('/survei/{slug}', [SurveyController::class, 'show']);
$router->post('/survei/{slug}', [SurveyController::class, 'submit'], ['rate:ticket', 'csrf']);