<?php

namespace App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanSelesaiResource\Pages;

use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanSelesaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengaduanSelesais extends ListRecords
{
    protected static string $resource = PengaduanSelesaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
