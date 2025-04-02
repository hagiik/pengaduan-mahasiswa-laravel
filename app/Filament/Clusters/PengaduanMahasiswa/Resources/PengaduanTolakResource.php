<?php

namespace App\Filament\Clusters\PengaduanMahasiswa\Resources;

use App\Filament\Clusters\PengaduanMahasiswa;
use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanTolakResource\Pages;
use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanTolakResource\RelationManagers;
use App\Models\Pengaduan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use App\Models\KategoriPengaduan;

class PengaduanTolakResource extends Resource
{
    protected static ?string $model = Pengaduan::class;

    protected static ?string $navigationIcon = 'heroicon-o-no-symbol';
    protected static ?int $navigationSort = 6;
    public static function getNavigationLabel(): string
    {
        return 'Pengaduan Ditolak';
    }

    protected static ?string $cluster = PengaduanMahasiswa::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Filter hanya status 'Ditolak'
                $query->whereHas('status', function($q) {
                    $q->where('status', 'Ditolak');
                });
                
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
                    // ->url(fn (Pengaduan $record): string => static::getUrl('replicate', ['record' => $record]))
                    ->url(fn (Pengaduan $record): string => static::getTanggapiPageUrl($record->id))
                    ->disabled(fn (Pengaduan $record): bool => in_array($record->status->status, ['Selesai', 'Ditolak'])),
                Tables\Actions\ViewAction::make('view')
                    ->label('View')
                    ->url(fn (Pengaduan $record): string => static::getViewPageUrl($record->id)),
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
    
    public static function getTanggapiPageUrl($recordId): string
    {
        return \App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanResource::getUrl('replicate', [
            'record' => $recordId,
        ]);
    }

    public static function getViewPageUrl($recordId): string
    {
        return \App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanResource::getUrl('view', [
            'record' => $recordId,
        ]);
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

    public static function getNavigationBadge(): ?string
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        if (!$user) {
            return '0';
        }
    
        $query = static::getModel()::query()
            ->whereHas('status', fn($q) => $q->where('status', 'Ditolak'));
        // Jika user bukan admin, filter berdasarkan kategori
        if (!static::userHasAdminRole($user)) {
            $categoryName = static::getCategoryFromUserRole($user);
            
            if ($categoryName) {
                $query->whereHas('kategori', fn($q) => $q->where('name', $categoryName));
            } else {
                return '0';
            }
        }
        
        return (string) $query->count();
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengaduanTolaks::route('/'),
            'create' => Pages\CreatePengaduanTolak::route('/create'),
            'edit' => Pages\EditPengaduanTolak::route('/{record}/edit'),
        ];
    }
}
