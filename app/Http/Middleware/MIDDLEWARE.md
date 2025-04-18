# Dokumentasi Middleware Sistem Pengaduan

## Middleware `CheckProfileComplete`

### Fungsi
Middleware ini memastikan user telah melengkapi profil mereka sebelum dapat mengakses fitur tertentu dalam sistem.

### Logika Pengecekan
Middleware akan memeriksa kelengkapan data profil user dengan memverifikasi field-field berikut:
- `nim` (Nomor Induk Mahasiswa)
- `telepon` (Nomor telepon)
- `fakultas` (Fakultas user)
- `prodi` (Program Studi user)

### Alur Kerja
1. Mendapatkan data user yang sedang login
2. Memeriksa apakah ada field yang masih null:
   ```php
   is_null($user->nim) ||
   is_null($user->telepon) ||
   is_null($user->fakultas) ||
   is_null($user->prodi)
   ```
3. Jika ada data yang belum lengkap:
   - Redirect ke halaman profil (`settings.profile`)
   - Menampilkan pesan warning:
     ```php
     ->with('status', 'warning')
     ->with('message', 'Silakan lengkapi profil Anda terlebih dahulu.')
     ```
4. Jika profil sudah lengkap, lanjutkan request

### Penggunaan
Tambahkan middleware ini pada route yang membutuhkan profil lengkap:
```php
Route::middleware(['auth', 'profile.complete'])->group(function() {
    // Route yang membutuhkan profil lengkap
});
```

## Middleware `CheckUserStatus`

### Fungsi
Middleware ini memeriksa status aktivasi akun user sebelum memberikan akses ke sistem.

### Logika Pengecekan
- Memeriksa apakah user terautentikasi (`Auth::check()`)
- Memeriksa status aktivasi user (`is_active == 0`)

### Alur Kerja
1. Memeriksa apakah user login dan status aktif:
   ```php
   Auth::check() && Auth::user()->is_active == 0
   ```
2. Jika akun tidak aktif:
   - Melakukan logout otomatis
   - Redirect ke halaman login
   - Menampilkan pesan error:
     ```php
     ->withErrors(['error' => 'Akun Anda telah dinonaktifkan oleh admin.'])
     ```
3. Jika akun aktif, lanjutkan request

### Penggunaan
Tambahkan middleware ini pada grup route yang membutuhkan autentikasi:
```php
Route::middleware(['auth', 'user.active'])->group(function() {
    // Route yang membutuhkan akun aktif
});
```

## Implementasi Middleware

1. Registrasikan middleware di `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    // ... middleware lainnya
    'profile.complete' => \App\Http\Middleware\CheckProfileComplete::class,
    'user.active' => \App\Http\Middleware\CheckUserStatus::class,
];
```

2. Contoh penggunaan di route:
```php
// Hanya untuk user dengan profil lengkap
Route::middleware(['auth', 'profile.complete'])->group(function() {
    Route::get('/pengaduan', [PengaduanController::class, 'index']);
});

// Untuk semua user yang aktif
Route::middleware(['auth', 'user.active'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

## Catatan Penting
1. Pastikan model User memiliki field:
   - `nim` (string)
   - `telepon` (string)
   - `fakultas` (string)
   - `prodi` (string)
   - `is_active` (boolean)

2. Untuk middleware `CheckProfileComplete`, pastikan:
   - Ada route bernama `settings.profile` untuk pengisian profil
   - View memiliki alert untuk menampilkan pesan warning

3. Untuk middleware `CheckUserStatus`, pastikan:
   - Ada route bernama `login` untuk redirect
   - View login dapat menampilkan error message