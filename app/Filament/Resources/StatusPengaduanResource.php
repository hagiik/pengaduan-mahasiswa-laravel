<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusPengaduanResource\Pages;
use App\Filament\Resources\StatusPengaduanResource\RelationManagers;
use App\Models\StatusPengaduan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusPengaduanResource extends Resource
{
    protected static ?string $model = StatusPengaduan::class;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-ripple';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListStatusPengaduans::route('/'),
            // 'create' => Pages\CreateStatusPengaduan::route('/create'),
            'edit' => Pages\EditStatusPengaduan::route('/{record}/edit'),
        ];
    }
}
