<?php

namespace App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanMenungguResource\Pages;

use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanMenungguResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengaduanMenunggus extends ListRecords
{
    protected static string $resource = PengaduanMenungguResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
