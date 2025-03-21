<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanggapan extends Model {
    use HasFactory;
    protected $table = 'tanggapan';
    protected $fillable = ['pengaduan_id', 'isi_tanggapan', 'status_id', 'user_id', 'penanggap_id', 'gambar_tanggapan'];

    public function pengaduan() {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id');
    }

    public function status() 
    {
        return $this->belongsTo(StatusPengaduan::class, 'status_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke tabel users (penanggap)
    public function penanggap()
    {
        return $this->belongsTo(User::class, 'penanggap_id');
    }
}
