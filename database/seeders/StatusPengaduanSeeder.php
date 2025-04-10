<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusPengaduanSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'Menunggu',
            'Diterima',
            'Diproses',
            'Selesai',
            'Ditolak',
        ];

        foreach ($statuses as $status) {
            DB::table('status_pengaduan')->updateOrInsert(
                ['status' => $status],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
