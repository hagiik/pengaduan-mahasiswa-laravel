<?php

namespace App\Filament\Widgets;

use App\Models\Pengaduan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class PengaduanWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected $listeners = ['refreshWidgets' => '$refresh'];
    protected function getTableQuery(): Builder
    {
        return Pengaduan::query()
            ->whereHas('status', function ($query) {
                $query->whereIn('status', ['Menunggu', 'Diterima', 'Diproses']);
            })
            ->latest();
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('startDate')
                ->form([
                    DatePicker::make('startDate')
                        ->label('Dari Tanggal')
                        ->displayFormat('d M Y')
                        ->native(false)
                        ->closeOnDateSelection(),
                ])
                ->query(fn (Builder $query, array $data) => 
                    $query->when($data['startDate'] ?? null, fn ($q, $date) => 
                        $q->whereDate('created_at', '>=', $date)
                    )
                ),

            Filter::make('endDate')
                ->form([
                    DatePicker::make('endDate')
                        ->label('Sampai Tanggal')
                        ->displayFormat('d M Y')
                        ->native(false)
                        ->closeOnDateSelection(),
                ])
                ->query(fn (Builder $query, array $data) => 
                    $query->when($data['endDate'] ?? null, fn ($q, $date) => 
                        $q->whereDate('created_at', '<=', $date)
                    )
                ),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery()) // Gunakan query yang sudah difilter
            ->filters($this->getTableFilters()) // Tambahkan filtering
            ->columns([
                Tables\Columns\TextColumn::make('no_pengaduan')
                    ->label('No. Pengaduan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('judul_pengaduan')
                    ->label('Judul')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelapor'),

                Tables\Columns\TextColumn::make('kategori.name')
                    ->label('Kategori')
                    ->badge(),

                Tables\Columns\TextColumn::make('status.status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu' => 'gray',
                        'Diterima' => 'primary',
                        'Diproses' => 'warning',
                        'Selesai' => 'success',
                        'Ditolak' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->url(fn (Pengaduan $record): string => route('filament.admin.pengaduan-mahasiswa.resources.pengaduans.view', $record->id)),
            ])
            ->emptyStateHeading('Belum ada pengaduan');
    }
}
