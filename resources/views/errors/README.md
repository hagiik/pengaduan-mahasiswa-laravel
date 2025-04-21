```markdown
# Dokumentasi Halaman Error Laravel

Dokumen ini berisi penjelasan mengenai jenis-jenis halaman error yang tersedia dalam aplikasi Laravel, lengkap dengan arti dan fungsinya agar mudah dipahami oleh developer maupun admin.

---

## 📌 Daftar Kode Error

| Kode | Nama Status              | Deskripsi                                                                 |
|------|---------------------------|---------------------------------------------------------------------------|
| 401  | Unauthorized              | Pengguna belum login atau tidak memiliki hak akses valid.                 |
| 402  | Payment Required          | Digunakan untuk layanan berbayar, akses ditolak sampai pembayaran dilakukan. |
| 403  | Forbidden                 | Pengguna tidak memiliki izin meskipun sudah login. Biasanya terkait hak akses. |
| 404  | Not Found                 | Halaman atau data yang dicari tidak ditemukan di server.                   |
| 419  | Page Expired              | CSRF Token tidak valid atau session kadaluarsa, biasanya terjadi pada form. |
| 429  | Too Many Requests         | Permintaan terlalu banyak dalam waktu singkat, rate-limit system aktif.   |
| 500  | Internal Server Error     | Kesalahan umum di server, bisa dari kode atau sistem bermasalah.          |
| 503  | Service Unavailable       | Server tidak tersedia sementara, umumnya saat maintenance atau overload.  |

---

## 💡 Contoh Kostumisasi

### 403 - Forbidden
- Menampilkan ilustrasi akses ditolak.
- Pesan ramah: "Oops! Kamu tidak memiliki izin untuk mengakses halaman ini."
- Tombol kembali ke beranda.

### 404 - Not Found
- Memberikan pesan sederhana bahwa halaman tidak ditemukan.
- Disarankan menambahkan link ke halaman utama agar user tidak tersesat.

### 503 - Service Unavailable
- Biasanya digunakan saat website sedang maintenance.
- Disertai gambar maintenance dan informasi estimasi waktu jika perlu.

---

## 🔥 Catatan Penggunaan Gambar
Letakkan file ilustrasi di dalam folder:

```
```
public/images/
```
```
Contoh:
- `maintenance.png` untuk halaman 503.
- `forbidden.png` untuk halaman 403.

---

## ✍️ Cara Ubah Tampilan
1. File template error bisa ditemukan di:
```
```
resources/views/errors/
```
```
2. Edit file sesuai kebutuhan, contoh:
- `403.blade.php` untuk akses ditolak.
- `404.blade.php` untuk halaman tidak ditemukan.
- `503.blade.php` untuk maintenance.

3. Gambar bisa dipanggil dengan sintaks:
```blade
<img src="{{ asset('images/namafile.png') }}" alt="Keterangan Gambar">
```

---

