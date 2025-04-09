# Dokumentasi Sistem Pengaduan Mahasiswa

## 1. Pendahuluan
Sistem Pengaduan Mahasiswa adalah aplikasi berbasis web yang memungkinkan mahasiswa menyampaikan keluhan kepada pihak fakultas. Sistem ini dibangun menggunakan **Laravel 12**, **Livewire**, dan **Filament** untuk tampilan admin.

---

## 2. Teknologi yang Digunakan
- **Laravel 12** – Backend framework.
- **Livewire** – Komponen dinamis tanpa JavaScript tambahan.
- **TailwindCSS** – Styling tampilan.
- **MySQL** – Database utama.
- **Filament** – Admin panel.
- **Spatie Laravel Permission** – Hak akses berbasis role.

---

## 3. Fitur Utama
### 3.1. Mahasiswa
- Registrasi dan login.
- Verifikasi email wajib sebelum mengirim pengaduan.
- Mengirim pengaduan berdasarkan kategori.
- Melihat status pengaduan:  
  **Menunggu → Diterima → Diproses → Selesai → Ditolak**
- Melihat riwayat pengaduan.

### 3.2. Admin
- Login sebagai admin.
- Melihat daftar pengaduan **hanya jika kategori pengaduan sesuai dengan rolenya**.
- Mengelola status pengaduan.
- Memberikan tanggapan.
- Mengelola kategori pengaduan dan user.

> 🔒 **Catatan:** Setiap admin hanya bisa melihat dan mengelola pengaduan dari kategori yang sesuai dengan `role` yang dimiliki.

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

Edit konfigurasi `.env`:

```env
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=admin@domainkampus.ac.id
MAIL_PASSWORD=yourpassword
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@domainkampus.ac.id
MAIL_FROM_NAME="Sistem Pengaduan"
```

> 📧 Disarankan menggunakan email resmi kampus seperti `admin@namauniversitas.ac.id`.

### 4.4. Generate Key dan Migrasi Database
```bash
php artisan key:generate
php artisan migrate --seed
```

### 4.5. Jalankan Aplikasi
```bash
php artisan serve
```

---

## 5. Struktur Database

### 5.1. Tabel pengaduan
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| no_pengaduan | STRING | Nomor unik |
| judul_pengaduan | STRING | Judul |
| slug | STRING | Slug unik |
| user_id | INT | Mahasiswa pengaju |
| kategori_id | INT | Kategori Pengaduan |
| status_id | INT | Status (lihat tabel status) |
| image | STRING (NULL) | File pendukung |
| created_at | TIMESTAMP | Tanggal dibuat |

### 5.2. Tabel tanggapan
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| pengaduan_id | INT | Relasi ke pengaduan |
| isi_tanggapan | TEXT | Isi |
| status_id | INT | Status terbaru |
| penanggap_id | INT | Admin/staf pemberi tanggapan |
| gambar_tanggapan | STRING (NULL) | File pendukung |

---

## 6. Status Pengaduan

| ID | Status     |
|----|------------|
| 1  | Menunggu   |
| 2  | Diterima   |
| 3  | Diproses   |
| 4  | Selesai    |
| 5  | Ditolak    |

---

## 7. Role dan Hak Akses

- Sistem menggunakan package **Spatie Laravel Permission**.
- Role default: `mahasiswa` dan beberapa `role admin` berdasarkan kategori.
- Saat admin login, hanya pengaduan yang **kategori-nya sesuai dengan rolenya** yang akan ditampilkan.

Contoh:
- Admin dengan role `Kemahasiswaan` hanya bisa melihat pengaduan dengan kategori `Kemahasiswaan`.
- Admin dengan role `Sarana` hanya bisa melihat pengaduan kategori `Sarana`.

> 🛡️ Ini memastikan privasi dan pemisahan tanggung jawab antardepartemen.

---

## 8. Autentikasi & Verifikasi Email

- Laravel digunakan untuk autentikasi.
- Mahasiswa **harus memverifikasi email** sebelum bisa membuat pengaduan.
- Email otomatis dikirim saat registrasi.

---

## 9. API Endpoint (Opsional)
| Method | Endpoint              | Deskripsi |
|--------|----------------------|------------|
| GET    | /pengaduan            | Semua pengaduan (admin) |
| POST   | /pengaduan            | Buat pengaduan baru |
| PUT    | /pengaduan/{id}       | Ubah status |
| GET    | /pengaduan/{id}       | Detail pengaduan |
| POST   | /tanggapan            | Tambah tanggapan |

---

## 10. Deployment

### 10.1. Build Frontend
```bash
npm run build
```

### 10.2. Optimasi
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```


---


## 12. Penutup
Sistem ini dibangun untuk mempermudah mahasiswa menyampaikan keluhan secara tertata dan efisien. Role-based visibility memastikan hanya pihak berwenang yang mengakses data sesuai tanggung jawabnya. Dengan Laravel 12 dan Livewire, sistem ini ringan, dinamis, dan siap dikembangkan lebih lanjut.
