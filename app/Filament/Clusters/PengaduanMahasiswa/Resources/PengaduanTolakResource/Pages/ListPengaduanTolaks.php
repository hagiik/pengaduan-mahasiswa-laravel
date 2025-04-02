<?php

namespace App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanTolakResource\Pages;

use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanTolakResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengaduanTolaks extends ListRecords
{
    protected static string $resource = PengaduanTolakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
