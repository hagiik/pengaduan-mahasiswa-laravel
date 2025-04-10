<?php

namespace App\Filament\Resources\StatusPengaduanResource\Pages;

use App\Filament\Resources\StatusPengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatusPengaduans extends ListRecords
{
    protected static string $resource = StatusPengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
