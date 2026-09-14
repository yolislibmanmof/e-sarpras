<?php

use App\Controllers\Admin\AssetCategoryController;
use App\Controllers\Admin\AssetConditionController;
use App\Controllers\Admin\AssetController;
use App\Controllers\Admin\AssetDisposalController;
use App\Controllers\Admin\AssetLabelController;
use App\Controllers\Admin\AuditLogController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\BackupController;
use App\Controllers\Admin\BorrowController;
use App\Controllers\Admin\BuildingController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\DepreciationController;
use App\Controllers\Admin\DispositionController;
use App\Controllers\Admin\FloorController;
use App\Controllers\Admin\ItemRequestController;
use App\Controllers\Admin\K3Controller;
use App\Controllers\Admin\LetterController;
use App\Controllers\Admin\LetterTemplateController;
use App\Controllers\Admin\MaintenanceController;
use App\Controllers\Admin\ReportController;
use App\Controllers\Admin\RoleController;
use App\Controllers\Admin\RoomBookingController;
use App\Controllers\Admin\RoomController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\StockCategoryController;
use App\Controllers\Admin\StockController;
use App\Controllers\Admin\SurveyController;
use App\Controllers\Admin\TechnicianController;
use App\Controllers\Admin\TicketController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\VendorController;

$router->get('/', [AuthController::class, 'root']);
$router->get('/login', [AuthController::class, 'showLogin'], ['guest']);
$router->post('/login', [AuthController::class, 'login'], ['guest', 'rate:login', 'csrf']);
$router->get('/logout', [AuthController::class, 'logout'], ['auth']);
$router->get('/dashboard', [DashboardController::class, 'index'], ['auth']);

// Master Gedung
$router->get('/gedung', [BuildingController::class, 'index'], ['auth', 'perm:building.view']);
$router->get('/gedung/tambah', [BuildingController::class, 'create'], ['auth', 'perm:building.create']);
$router->post('/gedung', [BuildingController::class, 'store'], ['auth', 'perm:building.create', 'csrf']);
$router->get('/gedung/{id}/ubah', [BuildingController::class, 'edit'], ['auth', 'perm:building.update']);
$router->post('/gedung/{id}/ubah', [BuildingController::class, 'update'], ['auth', 'perm:building.update', 'csrf']);
$router->post('/gedung/{id}/hapus', [BuildingController::class, 'destroy'], ['auth', 'perm:building.delete', 'csrf']);

// Master Lantai
$router->post('/gedung/{id}/lantai', [FloorController::class, 'store'], ['auth', 'perm:floor.create', 'csrf']);
$router->post('/lantai/{id}/hapus', [FloorController::class, 'destroy'], ['auth', 'perm:floor.delete', 'csrf']);

// Master Ruangan
$router->get('/ruangan', [RoomController::class, 'index'], ['auth', 'perm:room.view']);
$router->get('/ruangan/tambah', [RoomController::class, 'create'], ['auth', 'perm:room.create']);
$router->post('/ruangan', [RoomController::class, 'store'], ['auth', 'perm:room.create', 'csrf']);
$router->get('/ruangan/{id}/ubah', [RoomController::class, 'edit'], ['auth', 'perm:room.update']);
$router->post('/ruangan/{id}/ubah', [RoomController::class, 'update'], ['auth', 'perm:room.update', 'csrf']);
$router->post('/ruangan/{id}/hapus', [RoomController::class, 'destroy'], ['auth', 'perm:room.delete', 'csrf']);

// Master Kategori Aset
$router->get('/aset-kategori', [AssetCategoryController::class, 'index'], ['auth', 'perm:asset.view']);
$router->post('/aset-kategori', [AssetCategoryController::class, 'store'], ['auth', 'perm:asset.create', 'csrf']);
$router->post('/aset-kategori/{id}/hapus', [AssetCategoryController::class, 'destroy'], ['auth', 'perm:asset.delete', 'csrf']);

// Master Aset
$router->get('/aset', [AssetController::class, 'index'], ['auth', 'perm:asset.view']);
$router->get('/aset/tambah', [AssetController::class, 'create'], ['auth', 'perm:asset.create']);
$router->post('/aset', [AssetController::class, 'store'], ['auth', 'perm:asset.create', 'csrf']);

// Penyusutan Aset (WAJIB sebelum rute /aset/{id})
$router->get('/aset/penyusutan', [DepreciationController::class, 'index'], ['auth', 'perm:asset.view']);
$router->get('/aset/penyusutan/ekspor', [DepreciationController::class, 'export'], ['auth', 'perm:report.export']);
$router->post('/aset/{id}/umur', [DepreciationController::class, 'updateParams'], ['auth', 'perm:asset.update', 'csrf']);

$router->get('/aset/{id}', [AssetController::class, 'show'], ['auth', 'perm:asset.view']);
$router->get('/aset/{id}/ubah', [AssetController::class, 'edit'], ['auth', 'perm:asset.update']);
$router->post('/aset/{id}/ubah', [AssetController::class, 'update'], ['auth', 'perm:asset.update', 'csrf']);
$router->post('/aset/{id}/hapus', [AssetController::class, 'destroy'], ['auth', 'perm:asset.delete', 'csrf']);

// Label, Kondisi, dan Penghapusan Aset
$router->get('/aset/{id}/label/cetak', [AssetLabelController::class, 'print'], ['auth', 'perm:asset.label']);
$router->post('/aset/{id}/label', [AssetLabelController::class, 'store'], ['auth', 'perm:asset.label', 'csrf']);
$router->post('/label/{id}/hapus', [AssetLabelController::class, 'destroy'], ['auth', 'perm:asset.label', 'csrf']);
$router->post('/aset/{id}/kondisi', [AssetConditionController::class, 'store'], ['auth', 'perm:asset.update', 'csrf']);
$router->post('/aset/{id}/penghapusan', [AssetDisposalController::class, 'store'], ['auth', 'perm:asset.disposal', 'csrf']);
$router->post('/penghapusan/{id}/persetujuan', [AssetDisposalController::class, 'approve'], ['auth', 'perm:asset.disposal', 'csrf']);

// Tiket Kerusakan
$router->get('/tiket', [TicketController::class, 'index'], ['auth', 'perm:ticket.view']);
$router->get('/tiket/file/{id}', [TicketController::class, 'file'], ['auth', 'perm:ticket.view']);
$router->get('/tiket/{id}', [TicketController::class, 'show'], ['auth', 'perm:ticket.view']);
$router->post('/tiket/{id}/verifikasi', [TicketController::class, 'verify'], ['auth', 'perm:ticket.update', 'csrf']);
$router->post('/tiket/{id}/tolak', [TicketController::class, 'reject'], ['auth', 'perm:ticket.update', 'csrf']);
$router->post('/tiket/{id}/tugas', [TicketController::class, 'assign'], ['auth', 'perm:ticket.assign', 'csrf']);
$router->post('/tiket/{id}/status', [TicketController::class, 'updateStatus'], ['auth', 'perm:ticket.update', 'csrf']);

// Peminjaman Barang
$router->get('/peminjaman-barang', [BorrowController::class, 'index'], ['auth', 'perm:borrow.view']);
$router->get('/peminjaman-barang/{id}', [BorrowController::class, 'show'], ['auth', 'perm:borrow.view']);
$router->post('/peminjaman-barang/{id}/verifikasi', [BorrowController::class, 'verify'], ['auth', 'perm:borrow.update', 'csrf']);
$router->post('/peminjaman-barang/{id}/persetujuan', [BorrowController::class, 'approve'], ['auth', 'perm:borrow.approve', 'csrf']);
$router->post('/peminjaman-barang/{id}/serah', [BorrowController::class, 'handover'], ['auth', 'perm:borrow.update', 'csrf']);
$router->post('/peminjaman-barang/{id}/kembali', [BorrowController::class, 'returnBack'], ['auth', 'perm:borrow.return', 'csrf']);

// Peminjaman Ruangan
$router->get('/peminjaman-ruangan', [RoomBookingController::class, 'index'], ['auth', 'perm:room_booking.view']);
$router->get('/peminjaman-ruangan/{id}', [RoomBookingController::class, 'show'], ['auth', 'perm:room_booking.view']);
$router->post('/peminjaman-ruangan/{id}/verifikasi', [RoomBookingController::class, 'verify'], ['auth', 'perm:room_booking.update', 'csrf']);
$router->post('/peminjaman-ruangan/{id}/persetujuan', [RoomBookingController::class, 'approve'], ['auth', 'perm:room_booking.approve', 'csrf']);

// Stok Gudang
$router->get('/stok', [StockController::class, 'index'], ['auth', 'perm:stock.view']);
$router->get('/stok/tambah', [StockController::class, 'create'], ['auth', 'perm:stock.create']);
$router->post('/stok', [StockController::class, 'store'], ['auth', 'perm:stock.create', 'csrf']);
$router->get('/stok/{id}/ubah', [StockController::class, 'edit'], ['auth', 'perm:stock.update']);
$router->post('/stok/{id}/ubah', [StockController::class, 'update'], ['auth', 'perm:stock.update', 'csrf']);
$router->post('/stok/{id}/hapus', [StockController::class, 'destroy'], ['auth', 'perm:stock.delete', 'csrf']);
$router->post('/stok/{id}/mutasi', [StockController::class, 'move'], ['auth', 'perm:stock.update', 'csrf']);

// Kategori Stok
$router->get('/stok-kategori', [StockCategoryController::class, 'index'], ['auth', 'perm:stock.view']);
$router->post('/stok-kategori', [StockCategoryController::class, 'store'], ['auth', 'perm:stock.create', 'csrf']);
$router->post('/stok-kategori/{id}/hapus', [StockCategoryController::class, 'destroy'], ['auth', 'perm:stock.delete', 'csrf']);

// Permintaan Barang
$router->get('/permintaan-barang', [ItemRequestController::class, 'index'], ['auth', 'perm:item_request.view']);
$router->get('/permintaan-barang/{id}', [ItemRequestController::class, 'show'], ['auth', 'perm:item_request.view']);
$router->post('/permintaan-barang/{id}/verifikasi', [ItemRequestController::class, 'verify'], ['auth', 'perm:item_request.update', 'csrf']);
$router->post('/permintaan-barang/{id}/persetujuan', [ItemRequestController::class, 'approve'], ['auth', 'perm:item_request.approve', 'csrf']);
$router->post('/permintaan-barang/{id}/serahkan', [ItemRequestController::class, 'fulfill'], ['auth', 'perm:item_request.update', 'csrf']);

// Surat dan Disposisi
$router->get('/surat', [LetterController::class, 'index'], ['auth', 'perm:letter.view']);
$router->get('/surat/masuk/tambah', [LetterController::class, 'createIncoming'], ['auth', 'perm:letter.create']);
$router->post('/surat/masuk', [LetterController::class, 'storeIncoming'], ['auth', 'perm:letter.create', 'csrf']);
$router->get('/surat/keluar/tambah', [LetterController::class, 'createOutgoing'], ['auth', 'perm:letter.create']);
$router->post('/surat/keluar', [LetterController::class, 'storeOutgoing'], ['auth', 'perm:letter.create', 'csrf']);
$router->get('/surat/file/{id}', [LetterController::class, 'file'], ['auth', 'perm:letter.view']);
$router->get('/surat/{id}/cetak', [LetterController::class, 'printLetter'], ['auth', 'perm:letter.view']);
$router->get('/surat/{id}', [LetterController::class, 'show'], ['auth', 'perm:letter.view']);
$router->post('/surat/{id}/arsip', [LetterController::class, 'archive'], ['auth', 'perm:letter.archive', 'csrf']);
$router->post('/surat/{id}/disposisi/kirim', [LetterController::class, 'sendDisposition'], ['auth', 'perm:disposition.create', 'csrf']);
$router->post('/surat/{id}/tindak-lanjut', [LetterController::class, 'followUp'], ['auth', 'perm:letter.update', 'csrf']);
$router->post('/disposisi/{id}/isi', [DispositionController::class, 'fill'], ['auth', 'csrf']);

// Template Surat
$router->get('/template-surat', [LetterTemplateController::class, 'index'], ['auth', 'perm:template.manage']);
$router->post('/template-surat', [LetterTemplateController::class, 'store'], ['auth', 'perm:template.manage', 'csrf']);
$router->post('/template-surat/{id}', [LetterTemplateController::class, 'update'], ['auth', 'perm:template.manage', 'csrf']);
$router->post('/template-surat/{id}/hapus', [LetterTemplateController::class, 'destroy'], ['auth', 'perm:template.manage', 'csrf']);
$router->post('/template-surat/kop', [LetterTemplateController::class, 'storeHeader'], ['auth', 'perm:template.manage', 'csrf']);

// Pemeliharaan Berkala
$router->get('/pemeliharaan', [MaintenanceController::class, 'index'], ['auth', 'perm:maintenance.view']);
$router->get('/pemeliharaan/tambah', [MaintenanceController::class, 'create'], ['auth', 'perm:maintenance.create']);
$router->post('/pemeliharaan', [MaintenanceController::class, 'store'], ['auth', 'perm:maintenance.create', 'csrf']);
$router->get('/pemeliharaan/log', [MaintenanceController::class, 'logs'], ['auth', 'perm:maintenance.view']);
$router->post('/pemeliharaan/{id}/selesai', [MaintenanceController::class, 'complete'], ['auth', 'perm:maintenance.update', 'csrf']);
$router->post('/pemeliharaan/{id}/hapus', [MaintenanceController::class, 'destroy'], ['auth', 'perm:maintenance.delete', 'csrf']);

// K3L
$router->get('/k3/apar', [K3Controller::class, 'apar'], ['auth', 'perm:k3.view']);
$router->post('/k3/apar', [K3Controller::class, 'aparStore'], ['auth', 'perm:k3.create', 'csrf']);
$router->post('/k3/apar/{id}/hapus', [K3Controller::class, 'aparDestroy'], ['auth', 'perm:k3.delete', 'csrf']);
$router->get('/k3/limbah', [K3Controller::class, 'waste'], ['auth', 'perm:k3.view']);
$router->post('/k3/limbah', [K3Controller::class, 'wasteStore'], ['auth', 'perm:k3.create', 'csrf']);
$router->post('/k3/limbah/{id}/hapus', [K3Controller::class, 'wasteDestroy'], ['auth', 'perm:k3.delete', 'csrf']);
$router->get('/k3/simulasi', [K3Controller::class, 'drill'], ['auth', 'perm:k3.view']);
$router->post('/k3/simulasi', [K3Controller::class, 'drillStore'], ['auth', 'perm:k3.create', 'csrf']);
$router->post('/k3/simulasi/{id}/hapus', [K3Controller::class, 'drillDestroy'], ['auth', 'perm:k3.delete', 'csrf']);

// Survei Kepuasan
$router->get('/survei', [SurveyController::class, 'index'], ['auth', 'perm:survey.view']);
$router->get('/survei/{id}', [SurveyController::class, 'show'], ['auth', 'perm:survey.view']);
$router->post('/survei', [SurveyController::class, 'store'], ['auth', 'perm:survey.create', 'csrf']);
$router->post('/survei/{id}/pertanyaan', [SurveyController::class, 'addQuestion'], ['auth', 'perm:survey.update', 'csrf']);
$router->post('/survei/{id}/status', [SurveyController::class, 'toggle'], ['auth', 'perm:survey.update', 'csrf']);
$router->post('/survei/{id}/hapus', [SurveyController::class, 'destroy'], ['auth', 'perm:survey.delete', 'csrf']);
$router->post('/pertanyaan/{id}/hapus', [SurveyController::class, 'deleteQuestion'], ['auth', 'perm:survey.update', 'csrf']);

// Laporan dan Ekspor
$router->get('/laporan', [ReportController::class, 'index'], ['auth', 'perm:report.view']);
$router->get('/laporan/cetak', [ReportController::class, 'printView'], ['auth', 'perm:report.view']);
$router->get('/laporan/ekspor', [ReportController::class, 'export'], ['auth', 'perm:report.export']);

// Pengguna & Role (RBAC)
$router->get('/pengguna', [UserController::class, 'index'], ['auth', 'perm:user.manage']);
$router->get('/pengguna/tambah', [UserController::class, 'create'], ['auth', 'perm:user.manage']);
$router->post('/pengguna', [UserController::class, 'store'], ['auth', 'perm:user.manage', 'csrf']);
$router->get('/pengguna/{id}/ubah', [UserController::class, 'edit'], ['auth', 'perm:user.manage']);
$router->post('/pengguna/{id}/ubah', [UserController::class, 'update'], ['auth', 'perm:user.manage', 'csrf']);
$router->post('/pengguna/{id}/status', [UserController::class, 'toggle'], ['auth', 'perm:user.manage', 'csrf']);
$router->post('/pengguna/{id}/hapus', [UserController::class, 'destroy'], ['auth', 'perm:user.manage', 'csrf']);

$router->get('/role', [RoleController::class, 'index'], ['auth', 'perm:role.manage']);
$router->post('/role', [RoleController::class, 'store'], ['auth', 'perm:role.manage', 'csrf']);
$router->get('/role/{id}', [RoleController::class, 'show'], ['auth', 'perm:role.manage']);
$router->post('/role/{id}', [RoleController::class, 'updatePermissions'], ['auth', 'perm:role.manage', 'csrf']);
$router->post('/role/{id}/hapus', [RoleController::class, 'destroy'], ['auth', 'perm:role.manage', 'csrf']);

// Pengaturan, Audit Log, dan Backup
$router->get('/pengaturan', [SettingsController::class, 'index'], ['auth', 'perm:setting.manage']);
$router->post('/pengaturan', [SettingsController::class, 'store'], ['auth', 'perm:setting.manage', 'csrf']);
$router->get('/audit-log', [AuditLogController::class, 'index'], ['auth', 'perm:audit.view']);
$router->get('/backup', [BackupController::class, 'index'], ['auth', 'perm:backup.manage']);
$router->get('/backup/unduh', [BackupController::class, 'create'], ['auth', 'perm:backup.manage']);

// Teknisi dan Vendor
$router->get('/teknisi', [TechnicianController::class, 'index'], ['auth', 'perm:ticket.view']);
$router->post('/teknisi', [TechnicianController::class, 'store'], ['auth', 'perm:ticket.assign', 'csrf']);
$router->post('/teknisi/{id}/hapus', [TechnicianController::class, 'destroy'], ['auth', 'perm:ticket.assign', 'csrf']);
$router->get('/vendor', [VendorController::class, 'index'], ['auth', 'perm:ticket.view']);
$router->post('/vendor', [VendorController::class, 'store'], ['auth', 'perm:ticket.assign', 'csrf']);
$router->post('/vendor/{id}/hapus', [VendorController::class, 'destroy'], ['auth', 'perm:ticket.assign', 'csrf']);