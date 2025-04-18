# Dokumentasi PengaduanResource untuk Filament Admin Panel

## Overview
`PengaduanResource` merupakan Filament resource yang mengelola data pengaduan mahasiswa dalam sistem. Resource ini termasuk dalam cluster `PengaduanMahasiswa` dan menyediakan antarmuka CRUD untuk mengelola pengaduan.

## Fitur Utama
- Manajemen data pengaduan lengkap
- Filter otomatis berdasarkan role user
- Badge notifikasi jumlah pengaduan
- Integrasi dengan sistem autentikasi dan permission
- Tampilan responsive dengan berbagai komponen interaktif

## Struktur Resource

### Konfigurasi Dasar
```php
protected static ?string $model = Pengaduan::class;
protected static ?string $navigationIcon = 'heroicon-o-document-text';
protected static ?int $navigationSort = 1;
protected static ?string $cluster = PengaduanMahasiswa::class;
```

### Form Fields
Resource ini menggunakan form dengan field-field berikut:
1. **No Pengaduan**: Input text unik
2. **Judul Pengaduan**: Input text wajib
3. **Slug**: Input text unik otomatis
4. **Pelapor**: Select relationship ke model User
5. **Kategori**: Select relationship ke model KategoriPengaduan
6. **Isi Laporan**: Textarea wajib
7. **Gambar**: Upload multiple gambar (maksimal 3)
8. **Status**: Select relationship ke model StatusPengaduan

### Table Configuration
Tabel pengaduan memiliki kolom-kolom berikut:
- No Pengaduan (dengan tooltip)
- Judul Pengaduan (dibatasi 20 karakter)
- Nama Pelapor
- Kategori
- Status (dalam bentuk badge warna-warni)
- Tanggal dibuat dan diupdate (format relative time)

### Filter Data
Tabel secara otomatis memfilter data berdasarkan:
- Untuk admin: menampilkan semua pengaduan
- Untuk non-admin: hanya menampilkan pengaduan dengan kategori sesuai role user

## Sistem Permission
Resource ini mengimplementasikan `HasShieldPermissions` dengan permission:
- view
- view_any
- create
- update
- delete 
- replicate

## Custom Methods

### `userHasAdminRole()`
Memeriksa apakah user memiliki role admin atau staff:
```php
$user->hasRole(config('filament-shield.super_admin.name')) || $user->hasRole('staff')
```

### `getCategoryFromUserRole()`
Mendapatkan kategori pengaduan berdasarkan role user dengan:
1. Mapping nama role ke kategori pengaduan
2. Pencarian case-insensitive
3. Pencarian partial match

### `getNavigationBadge()`
Menampilkan badge jumlah pengaduan di menu navigasi:
- Untuk admin: total semua pengaduan
- Untuk non-admin: hanya pengaduan dengan kategori sesuai role

## Pages
Resource ini memiliki beberapa halaman khusus:
1. **List**: Tampilan utama daftar pengaduan
2. **Create**: Form pembuatan pengaduan baru
3. **Replicate**: Halaman khusus untuk menanggapi pengaduan
4. **Edit**: Form edit pengaduan
5. **View**: Detail pengaduan

## Action Buttons
Setiap baris pengaduan memiliki action buttons:
1. **Edit**: Mengubah data pengaduan
2. **Tanggapi**: Membuat tanggapan baru (disabled untuk status Selesai/Ditolak)
3. **View**: Melihat detail pengaduan
4. **Delete**: Menghapus pengaduan

## Fitur Khusus
1. **Auto-filter Data**: Data yang ditampilkan disesuaikan dengan role user
2. **Status Badge**: Visualisasi status dengan warna berbeda
3. **Upload Multiple**: Dapat mengupload hingga 3 gambar sekaligus
4. **Tooltip**: Menampilkan teks lengkap untuk kolom yang dipotong
5. **Sorting Default**: Diurutkan berdasarkan tanggal terbaru

## Catatan Implementasi
1. Pastikan model `Pengaduan` memiliki relasi ke:
   - User (pelapor)
   - KategoriPengaduan
   - StatusPengaduan

2. Konfigurasi permission harus sesuai dengan Filament Shield

3. Untuk upload gambar, pastikan:
   - Folder `public/tanggapan` ada dan writable
   - Konfigurasi filesystem menggunakan disk public

4. Role dan kategori harus sinkron untuk filter otomatis berfungsi
