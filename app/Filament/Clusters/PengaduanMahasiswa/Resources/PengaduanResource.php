<?php

namespace App\Filament\Clusters\PengaduanMahasiswa\Resources;

use App\Filament\Clusters\PengaduanMahasiswa;
use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanResource\Pages;
use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanResource\RelationManagers;
use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
class PengaduanResource extends Resource implements HasShieldPermissions
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
                    ->label('Nama Pelapor')
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
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                
                if (!static::userHasAdminRole($user)) {
                    $categoryName = static::getCategoryFromUserRole($user);
                    
                    if ($categoryName) {
                        $query->whereHas('kategori', function($q) use ($categoryName) {
                            $q->where('name', $categoryName);
                        });
                    } else {
                        // Jika tidak ada kategori yang sesuai, tampilkan kosong
                        $query->where('id', 0);
                    }
                }
            })
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make('replicate')
                    ->label('Tanggapi')
                    ->url(fn (Pengaduan $record): string => static::getUrl('replicate', ['record' => $record]))
                    ->disabled(fn (Pengaduan $record): bool => in_array($record->status->status, ['Selesai', 'Ditolak'])),
                Tables\Actions\ViewAction::make('view')
                    ->label('View')
                    ->url(fn (Pengaduan $record): string => static::getUrl('view', ['record' => $record])),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
    
    /**
     * Check if user has admin role (super_admin or staff)
     */
    protected static function userHasAdminRole($user): bool
    {
        return $user->hasRole(config('filament-shield.super_admin.name')) || 
               $user->hasRole('staff');
    }
    
    /**
     * Get category name based on user role
     */
    protected static function getCategoryFromUserRole($user): ?string
    {
        static $categories = null;
        
        // Cache kategori untuk performa
        if ($categories === null) {
            $categories = KategoriPengaduan::pluck('name')->mapWithKeys(function ($category) {
                return [str($category)->lower()->toString() => $category];
            })->toArray();
        }
        
        foreach ($user->roles as $role) {
            $roleName = str($role->name)->lower()->toString();
            
            // Cek kesamaan persis
            if (array_key_exists($roleName, $categories)) {
                return $categories[$roleName];
            }
            
            // Cek kesamaan partial (opsional)
            foreach ($categories as $catLower => $category) {
                if (str_contains($roleName, $catLower) || str_contains($catLower, $roleName)) {
                    return $category;
                }
            }
        }
        
        return null;
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
            'create' => Pages\CreatePengaduan::route('/create'),
            'replicate' => Pages\TanggapiPengaduan::route('/{record}/tanggapi'),
            'edit' => Pages\EditPengaduan::route('/{record}/edit'),
            'view' => Pages\ViewPengaduan::route('/{record}/view'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'replicate'
        ];
    }
}
