<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class K3Controller extends Controller
{
    public function apar(): void
    {
        $this->adminView('admin/pages/k3/apar', [
            'title' => 'APAR',
            'rows'  => $this->db()->select(
                "SELECT ap.*, b.name AS building_name FROM apar ap LEFT JOIN buildings b ON b.id = ap.building_id ORDER BY ap.expiry_date ASC"
            ),
        ]);
    }

    public function aparStore(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, ['code' => 'required|max:100']);

        if ($errors !== []) {
            Session::flash('error', 'Kode APAR wajib diisi.');
            $this->redirect(admin_url('/k3/apar'));
        }

        $id = $this->db()->insert('apar', [
            'code'              => $data['code'],
            'building_id'       => ($data['building_id'] ?? '') === '' ? null : (int) $data['building_id'],
            'room_id'           => ($data['room_id'] ?? '') === '' ? null : (int) $data['room_id'],
            'apar_type'         => $data['apar_type'] ?? null,
            'capacity'          => $data['capacity'] ?? null,
            'location'          => $data['location'] ?? null,
            'expiry_date'       => ($data['expiry_date'] ?? '') === '' ? null : $data['expiry_date'],
            'last_service_date' => ($data['last_service_date'] ?? '') === '' ? null : $data['last_service_date'],
            'next_service_date' => ($data['next_service_date'] ?? '') === '' ? null : $data['next_service_date'],
            'status'            => $data['status'] ?? 'Baik',
            'notes'             => $data['notes'] ?? null,
            'created_by'        => auth_id(),
        ]);

        AuditLogger::log('k3.apar.create', 'k3', $id);
        Session::flash('success', 'APAR berhasil dicatat.');
        $this->redirect(admin_url('/k3/apar'));
    }

    public function aparDestroy(string $id): void
    {
        $this->db()->delete('apar', ['id' => $id]);
        AuditLogger::log('k3.apar.delete', 'k3', $id);
        Session::flash('success', 'APAR berhasil dihapus.');
        $this->redirect(admin_url('/k3/apar'));
    }

    public function waste(): void
    {
        $this->adminView('admin/pages/k3/waste', [
            'title' => 'Log Limbah',
            'rows'  => $this->db()->select('SELECT * FROM waste_logs ORDER BY handling_date DESC'),
        ]);
    }

    public function wasteStore(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, ['waste_type' => 'required|max:191']);

        if ($errors !== []) {
            Session::flash('error', 'Jenis limbah wajib diisi.');
            $this->redirect(admin_url('/k3/limbah'));
        }

        $id = $this->db()->insert('waste_logs', [
            'waste_type'      => $data['waste_type'],
            'source'          => $data['source'] ?? null,
            'volume'          => $data['volume'] ?? null,
            'handling_method' => $data['handling_method'] ?? null,
            'handling_date'   => ($data['handling_date'] ?? '') === '' ? null : $data['handling_date'],
            'vendor_name'     => $data['vendor_name'] ?? null,
            'notes'           => $data['notes'] ?? null,
            'created_by'      => auth_id(),
        ]);

        AuditLogger::log('k3.waste.create', 'k3', $id);
        Session::flash('success', 'Log limbah berhasil dicatat.');
        $this->redirect(admin_url('/k3/limbah'));
    }

    public function wasteDestroy(string $id): void
    {
        $this->db()->delete('waste_logs', ['id' => $id]);
        AuditLogger::log('k3.waste.delete', 'k3', $id);
        Session::flash('success', 'Log limbah berhasil dihapus.');
        $this->redirect(admin_url('/k3/limbah'));
    }

    public function drill(): void
    {
        $this->adminView('admin/pages/k3/drill', [
            'title' => 'Simulasi Bencana',
            'rows'  => $this->db()->select('SELECT * FROM disaster_drills ORDER BY drill_date DESC'),
        ]);
    }

    public function drillStore(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, ['drill_type' => 'required|max:191']);

        if ($errors !== []) {
            Session::flash('error', 'Jenis simulasi wajib diisi.');
            $this->redirect(admin_url('/k3/simulasi'));
        }

        $id = $this->db()->insert('disaster_drills', [
            'drill_type'      => $data['drill_type'],
            'drill_date'      => ($data['drill_date'] ?? '') === '' ? null : $data['drill_date'],
            'location'        => $data['location'] ?? null,
            'participant_count' => (int) ($data['participant_count'] ?? 0),
            'result'          => $data['result'] ?? null,
            'notes'           => $data['notes'] ?? null,
            'created_by'      => auth_id(),
        ]);

        AuditLogger::log('k3.drill.create', 'k3', $id);
        Session::flash('success', 'Simulasi bencana berhasil dicatat.');
        $this->redirect(admin_url('/k3/simulasi'));
    }

    public function drillDestroy(string $id): void
    {
        $this->db()->delete('disaster_drills', ['id' => $id]);
        AuditLogger::log('k3.drill.delete', 'k3', $id);
        Session::flash('success', 'Simulasi bencana berhasil dihapus.');
        $this->redirect(admin_url('/k3/simulasi'));
    }
}