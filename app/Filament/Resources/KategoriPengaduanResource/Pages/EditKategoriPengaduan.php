<?php

namespace App\Filament\Resources\KategoriPengaduanResource\Pages;

use App\Filament\Resources\KategoriPengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriPengaduan extends EditRecord
{
    protected static string $resource = KategoriPengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
