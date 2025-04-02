<?php

namespace App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanSelesaiResource\Pages;

use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanSelesaiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengaduanSelesai extends EditRecord
{
    protected static string $resource = PengaduanSelesaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
