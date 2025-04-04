<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\PengaduanWidget;
use App\Models\Pengaduan;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Form;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Dashboard';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Data')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    $this->dispatch('refreshWidgets');
                }),
        ];
    }
}