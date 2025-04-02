<?php

namespace App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanTolakResource\Pages;

use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanTolakResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengaduanTolak extends EditRecord
{
    protected static string $resource = PengaduanTolakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
