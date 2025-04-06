<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Pengaduan;
use App\Models\StatusPengaduan;

class HomeController extends Controller
{
    public function index()
    {
        // Hitung total user dengan role mahasiswa
        $roleMahasiswa = Role::where('name', 'mahasiswa')->first();
        $totalMahasiswa = $roleMahasiswa ? $roleMahasiswa->users()->count() : 0;

        // Hitung total pengaduan dengan status 'Selesai'
        $statusSelesai = StatusPengaduan::where('status', 'Selesai')->first();
        $totalPengaduanSelesai = $statusSelesai 
            ? Pengaduan::where('status_id', $statusSelesai->id)->count() 
            : 0;

        // Hitung total semua pengaduan (tanpa filter status)
        $totalSemuaPengaduan = Pengaduan::count();

        return view('page.landing.home', [
            'totalMahasiswa' => $totalMahasiswa,
            'totalPengaduanSelesai' => $totalPengaduanSelesai,
            'totalSemuaPengaduan' => $totalSemuaPengaduan
        ]);
    }
}