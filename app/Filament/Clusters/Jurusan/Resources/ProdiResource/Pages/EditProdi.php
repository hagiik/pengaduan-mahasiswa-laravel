<?php

namespace App\Filament\Clusters\Jurusan\Resources\ProdiResource\Pages;

use App\Filament\Clusters\Jurusan\Resources\ProdiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProdi extends EditRecord
{
    protected static string $resource = ProdiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
