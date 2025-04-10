<?php

namespace App\Filament\Clusters\Jurusan\Resources\FakultasResource\Pages;

use App\Filament\Clusters\Jurusan\Resources\FakultasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFakultas extends EditRecord
{
    protected static string $resource = FakultasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
