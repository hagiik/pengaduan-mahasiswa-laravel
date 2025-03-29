<?php

namespace App\Http\Resources;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengaduanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'no_pengaduan' => $this->no_pengaduan,
            'judul' => $this->judul_pengaduan,
            'slug' => $this->slug,
            'pelapor' => [
                'name' => $this->user->name ?? null, // Jika ada relasi user
            ],
            'kategori' => [
                'name' => $this->kategori->name ?? null, // Jika ada relasi kategori
            ],
            'isi_laporan' => $this->isi_laporan,
            'image_url' => $this->image ? asset('storage/' . $this->image) : null,
            'status' => [
                'name' => $this->status->status ?? null, // Jika ada relasi status
            ],
            'dibuat_pada' => $this->created_at->format('Y-m-d H:i:s'),
            'diperbarui_pada' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }

 
}
