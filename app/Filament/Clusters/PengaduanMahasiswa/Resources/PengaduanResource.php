<?php

namespace App\Filament\Clusters\PengaduanMahasiswa\Resources;

use App\Filament\Clusters\PengaduanMahasiswa;
use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanResource\Pages;
use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanResource\RelationManagers;
use App\Models\Pengaduan;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengaduanResource extends Resource
{
    protected static ?string $model = Pengaduan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    public static function getNavigationLabel(): string
    {
        return 'Seluruh Pengaduan Mahasiswa';
    }

    protected static ?string $cluster = PengaduanMahasiswa::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_pengaduan')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('judul_pengaduan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('kategori_id')
                    ->relationship('kategori', 'name')
                    ->required(),
                Forms\Components\Textarea::make('isi_laporan')
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('tanggapan')
                    ->visibility('public')
                    ->multiple()
                    ->maxParallelUploads(3)
                    ->nullable(),
                Forms\Components\Select::make('status_id')
                    ->relationship('status', 'status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_pengaduan')
                    ->sortable()
                    ->searchable()
                    ->limit(10)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('judul_pengaduan')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelapor')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status.status')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu' => 'gray',
                        'Diterima' => 'primary',
                        'Diproses' => 'warning',
                        'Selesai' => 'success',
                        'Ditolak' => 'danger',
                    })
                    ->formatStateUsing(function ($state) {
                        return ucfirst($state);
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('tanggapi') // Action untuk menanggapi
                    ->label('Tanggapi')
                    ->url(fn (Pengaduan $record): string => static::getUrl('tanggapi', ['record' => $record]))
                    ->disabled(fn (Pengaduan $record): bool => in_array($record->status->status, ['Selesai', 'Ditolak'])),
                Tables\Actions\Action::make('View') // Action untuk menanggapi
                    ->label('View'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengaduans::route('/'),
            // 'create' => Pages\CreatePengaduan::route('/create'),
            'tanggapi' => Pages\TanggapiPengaduan::route('/{record}/tanggapi'),
            // 'edit' => Pages\EditPengaduan::route('/{record}/edit'),
        ];
    }
}
