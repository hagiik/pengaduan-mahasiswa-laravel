<?php

namespace App\Filament\Resources\KategoriPengaduanResource\Pages;

use App\Filament\Resources\KategoriPengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriPengaduans extends ListRecords
{
    protected static string $resource = KategoriPengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
