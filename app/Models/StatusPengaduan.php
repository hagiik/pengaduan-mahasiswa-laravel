<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPengaduan extends Model {
    use HasFactory;
    
    protected $table = 'status_pengaduan';

    protected $fillable = ['status'];

    public function pengaduan() {
        return $this->hasMany(Pengaduan::class, 'status_id');
    }

    public function tanggapan() {
        return $this->hasMany(Tanggapan::class, 'status_id'); // Perbaikan: gunakan 'status_id' sebagai foreign key
    }
}

