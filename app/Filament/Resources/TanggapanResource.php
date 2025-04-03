<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TanggapanResource\Pages;
use App\Filament\Resources\TanggapanResource\RelationManagers;
use App\Models\Tanggapan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TanggapanResource extends Resource
{
    protected static ?string $model = Tanggapan::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $modelLabel = 'Tanggapan';

    protected static ?string $navigationLabel = 'Tanggapan';

    protected static ?string $navigationGroup = 'Pengaduan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pengaduan_id')
                    ->relationship('pengaduan', 'judul_pengaduan')
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                Forms\Components\RichEditor::make('isi_tanggapan')
                    ->required()
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('status_id')
                    ->relationship('status', 'status')
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                Forms\Components\Select::make('user_id')
                    ->relationship('users', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                Forms\Components\Select::make('penanggap_id')
                    ->relationship('penanggap', 'name')
                    ->label('Penanggap')
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                Forms\Components\FileUpload::make('gambar_tanggapan')
                    ->multiple()
                    ->image()
                    ->directory('tanggapan-images')
                    ->downloadable()
                    ->openable()
                    ->preserveFilenames(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengaduan.judul_pengaduan')
                    ->label('Judul Pengaduan')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 30 ? $state : null;
                    }),
                    
                Tables\Columns\TextColumn::make('isi_tanggapan')
                    ->html()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = strip_tags($column->getState());
                        return strlen($state) > 50 ? $state : null;
                    }),
                    
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
                    
                Tables\Columns\TextColumn::make('users.name')
                    ->label('Pengadu')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('penanggap.name')
                    ->label('Penanggap')
                    ->searchable(),
                    
                Tables\Columns\ImageColumn::make('gambar_tanggapan')
                    ->label('Gambar')
                    ->stacked()
                    ->circular()
                    ->limit(3)
                    ->limitedRemainingText(isSeparate: true),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->relationship('status', 'status')
                    ->searchable()
                    ->preload()
                    ->label('Status Pengaduan'),
                
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('users', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Pengadu'),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when($data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->label('Rentang Tanggal'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListTanggapans::route('/'),
            'create' => Pages\CreateTanggapan::route('/create'),
            // 'view' => Pages\View::route('/{record}'),
            'edit' => Pages\EditTanggapan::route('/{record}/edit'),
        ];
    }
}