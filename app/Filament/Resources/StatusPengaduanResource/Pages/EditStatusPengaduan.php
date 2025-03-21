<?php

namespace App\Filament\Resources\StatusPengaduanResource\Pages;

use App\Filament\Resources\StatusPengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatusPengaduan extends EditRecord
{
    protected static string $resource = StatusPengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
