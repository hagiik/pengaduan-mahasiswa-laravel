<?php

namespace App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanProsesResource\Pages;

use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanProsesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengaduanProses extends ListRecords
{
    protected static string $resource = PengaduanProsesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
