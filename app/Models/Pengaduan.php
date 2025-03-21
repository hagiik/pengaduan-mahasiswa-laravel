<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model {
    use HasFactory;

    protected $table = 'pengaduan';
    protected $fillable = ['no_pengaduan', 'judul_pengaduan', 'slug', 'user_id', 'kategori_id', 'isi_laporan', 'image', 'status_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function kategori() {
        return $this->belongsTo(KategoriPengaduan::class, 'kategori_id');
    }

    public function status() 
    {
        return $this->belongsTo(StatusPengaduan::class, 'status_id');
    }

    public function tanggapan() {
        return $this->hasMany(Tanggapan::class);
    }
}

