<?php

namespace App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanResource\Pages;

use App\Models\Pengaduan;
use App\Models\Tanggapan;
use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanResource;
use Filament\Resources\Pages\Page;
use Filament\Actions;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class ViewPengaduan extends Page
{
    // use HasPageShield;
    protected static string $resource = PengaduanResource::class;
    protected static string $view = 'filament.clusters.pengaduan-mahasiswa.resources.pengaduan-resource.pages.view-pengaduan';

    public Pengaduan $pengaduan;
    public array $tanggapans = [];

    public function mount($record): void
    {
        $this->pengaduan = Pengaduan::with('user', 'status')->findOrFail($record);
        $this->tanggapans = Tanggapan::where('pengaduan_id', $record)
            ->with('penanggap', 'status')
            ->latest()
            ->get()
            ->toArray();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('tanggapi')
                ->label('Tanggapi Pengaduan')
                ->url(fn () => static::getResource()::getUrl('replicate', ['record' => $this->pengaduan->id]))
                // ->url(fn (Pengaduan $record): string => static::getUrl('replicate', ['record' => $record]))
                ->visible(fn () => $this->pengaduan->status->status !== 'Selesai'),
        ];
    }
}
