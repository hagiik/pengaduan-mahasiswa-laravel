# Dokumentasi Sistem Pengaduan Mahasiswa

## 1. Pendahuluan
Sistem Pengaduan Mahasiswa adalah sebuah aplikasi berbasis web yang memungkinkan mahasiswa untuk mengajukan keluhan atau pengaduan kepada pihak fakultas. Sistem ini dibangun menggunakan **Laravel 12** sebagai backend dan **Livewire** untuk interaksi frontend secara dinamis tanpa perlu reload halaman.

---

## 2. Teknologi yang Digunakan
- **Laravel 12**: Framework PHP untuk backend.
- **Livewire**: Library untuk membuat komponen dinamis tanpa JavaScript tambahan.
- **TailwindCSS**: Framework CSS untuk styling tampilan.
- **MySQL**: Database yang digunakan untuk menyimpan data pengaduan.
- **Filament**: Dashboard admin untuk mengelola data.

---

## 3. Fitur Utama
### 3.1. Mahasiswa
- Login dan Register (jika diperlukan).
- Mengajukan pengaduan dengan mengisi formulir.
- Melihat status pengaduan (diproses, selesai, ditolak).
- Notifikasi jika ada perubahan status pengaduan.
- Melihat riwayat pengaduan.

### 3.2. Admin
- Login sebagai admin untuk mengelola pengaduan.
- Melihat daftar pengaduan mahasiswa.
- Mengubah status pengaduan.
- Mengelola kategori pengaduan.
- Memberikan tanggapan atas pengaduan.
- Mengirim notifikasi ke mahasiswa.

---

## 4. Instalasi
### 4.1. Clone Repository
```bash
git clone https://github.com/username/repository.git
cd repository
```

### 4.2. Install Dependency
```bash
composer install
npm install
```

### 4.3. Konfigurasi Environment
```bash
cp .env.example .env
```
Lalu sesuaikan konfigurasi database di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

### 4.4. Generate Key dan Migrasi Database
```bash
php artisan key:generate
php artisan migrate --seed
```

### 4.5. Jalankan Aplikasi
```bash
php artisan serve
```
Akses aplikasi di `http://127.0.0.1:8000`

---

## 5. Struktur Database
### 5.1. Tabel **pengaduan**
| Kolom         | Tipe Data    | Deskripsi |
|--------------|------------|------------|
| id           | INT (AUTO)  | ID Pengaduan |
| no_pengaduan | STRING      | Nomor unik pengaduan |
| judul_pengaduan | STRING   | Judul Pengaduan |
| slug         | STRING      | Slug unik |
| user_id      | INT         | ID Mahasiswa yang mengajukan |
| kategori_id  | INT         | ID Kategori Pengaduan |
| isi_laporan  | TEXT        | Isi Pengaduan |
| image        | STRING (NULL) | Gambar pendukung |
| status_id    | INT         | ID Status Pengaduan |
| created_at   | TIMESTAMP   | Tanggal Pengajuan |
| updated_at   | TIMESTAMP   | Tanggal Perubahan |

### 5.2. Tabel **tanggapan**
| Kolom            | Tipe Data    | Deskripsi |
|-----------------|------------|------------|
| id              | INT (AUTO)  | ID Tanggapan |
| pengaduan_id    | INT         | ID Pengaduan terkait |
| isi_tanggapan   | TEXT        | Isi Tanggapan |
| status_id       | INT         | ID Status Pengaduan setelah tanggapan |
| user_id         | INT (NULL)  | ID Pelapor yang memberikan tanggapan |
| penanggap_id    | INT (NULL)  | ID Admin atau staf yang memberikan tanggapan |
| gambar_tanggapan | STRING (NULL) | Gambar pendukung tanggapan |
| created_at      | TIMESTAMP   | Tanggal Tanggapan |
| updated_at      | TIMESTAMP   | Tanggal Perubahan |

---

## 6. API Endpoint
| Method | Endpoint              | Deskripsi |
|--------|----------------------|------------|
| GET    | /pengaduan            | Menampilkan semua pengaduan |
| POST   | /pengaduan            | Membuat pengaduan baru |
| GET    | /pengaduan/{id}       | Melihat detail pengaduan |
| PUT    | /pengaduan/{id}       | Mengubah status pengaduan |
| DELETE | /pengaduan/{id}       | Menghapus pengaduan |
| GET    | /tanggapan/{id}       | Melihat tanggapan suatu pengaduan |
| POST   | /tanggapan            | Memberikan tanggapan pada pengaduan |

---



## 7. Kesimpulan
Sistem Pengaduan Mahasiswa ini mempermudah mahasiswa dalam menyampaikan keluhan dan memungkinkan admin untuk mengelola pengaduan dengan lebih efisien. Dengan penggunaan Laravel 12 dan Livewire, aplikasi ini memiliki performa yang baik dan responsif.

