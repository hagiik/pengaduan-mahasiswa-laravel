<?php

namespace App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanTerimaResource\Pages;

use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanTerimaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengaduanTerima extends EditRecord
{
    protected static string $resource = PengaduanTerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
