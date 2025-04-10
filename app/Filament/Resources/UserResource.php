<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Carbon;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $activeNavigationIcon = 'heroicon-o-user';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Admin Access';
    protected static ?string $navigationLabel = 'User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nimd')
                    ->maxLength(255),
                Forms\Components\TextInput::make('telepon')
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->preload(),
                Forms\Components\Select::make('fakultas_id')
                    ->relationship('fakultas', 'name')
                    ->label('Fakultas')
                    ->preload(),
                Forms\Components\Select::make('prodi_id')
                    ->relationship('prodi', 'name')
                    ->label('Prodi')
                    ->preload(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Akun')
                    ->onIcon('heroicon-m-check-badge')
                    ->offIcon('heroicon-m-no-symbol'),
                Forms\Components\Toggle::make('verifikasi_email')
                    ->label('Verifikasi Email')
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark')
                    ->default(true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('email_verified_at', $state ? Carbon::now() : null);
                    }),
                
                Forms\Components\Hidden::make('email_verified_at')
                    ->default(fn () => now())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('nimd')
                    ->searchable(),
                TextColumn::make('telepon')
                    ->searchable()
                    ->url(function ($record) {
                        // Bersihkan nomor telepon dari karakter non-digit
                        $phone = preg_replace('/[^0-9]/', '', $record->telepon);
                        
                        // Hilangkan angka 0 di depan jika ada
                        if (str_starts_with($phone, '0')) {
                            $phone = substr($phone, 1);
                        }
                        
                        // Buat URL WhatsApp
                        return "https://api.whatsapp.com/send?phone=62{$phone}";
                    })
                    ->openUrlInNewTab() // Buka di tab baru
                    ->disableClick(fn ($record) => empty($record->telepon))
                    ->icon('heroicon-s-phone') // Tambahkan ikon telepon
                    ->iconColor('success') // Warna hijau
                    ->tooltip('Klik untuk chat via WhatsApp'),
                ToggleColumn::make('is_active')
                    ->label('Status Akun')
                    ->onIcon('heroicon-m-check-badge')
                    ->offIcon('heroicon-m-no-symbol'),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->separator(', '),
                TextColumn::make('fakultas.name')
                    ->label('Fakultas')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('prodi.name')
                    ->label('Prodi')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('email_verified_at')
                    ->boolean()
                    ->label('Email Verifikasi')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
