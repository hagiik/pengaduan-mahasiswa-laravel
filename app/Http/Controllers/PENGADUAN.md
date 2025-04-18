# Dokumentasi Sistem Pengaduan

## `PengaduanController`

Controller ini menangani seluruh alur pengaduan mulai dari pembuatan, penyimpanan, hingga pengelolaan pengaduan oleh mahasiswa.

### Fitur Utama
- Membuat pengaduan baru
- Melihat daftar pengaduan
- Melihat detail pengaduan
- Mengedit pengaduan
- Menghapus pengaduan
- Notifikasi email ke admin terkait

### Method Utama

#### `index()`
- **Fungsi**: Menampilkan daftar pengaduan milik user yang login
- **Filter**: 
  - Hanya menampilkan pengaduan milik user yang login
  - Diurutkan berdasarkan tanggal terbaru
  - Paginasi 10 item per halaman
- **View**: `Page.Pengaduan.Pengaduan-mahasiswa-list`

#### `create()`
- **Fungsi**: Menampilkan form pembuatan pengaduan
- **Data yang dibutuhkan**:
  - Daftar kategori pengaduan
  - Daftar status pengaduan
- **View**: `Page.Pengaduan.Pengaduan-mahasiswa-create`

#### `store(Request $request)`
- **Fungsi**: Menyimpan pengaduan baru
- **Validasi**:
  ```php
  [
      'judul_pengaduan' => 'required|string|max:255',
      'kategori_id' => 'required|exists:kategori_pengaduan,id',
      'isi_laporan' => 'required|string',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048'
  ]
  ```
- **Proses**:
  1. Generate nomor pengaduan unik (format: PENG-XXXXX-XXX-YYYYMMDD)
  2. Handle upload gambar (jika ada)
  3. Buat slug dari judul pengaduan
  4. Simpan data pengaduan
  5. Buat tanggapan otomatis
  6. Kirim notifikasi email ke admin dengan role sesuai kategori

- **Notifikasi Email**:
  - Menggunakan job queue `SendPengaduanEmail`
  - Dikirim ke user dengan role yang sesuai dengan kategori pengaduan
  - Termasuk super_admin dan admin

#### `show(string $slug)`
- **Fungsi**: Menampilkan detail pengaduan
- **Data yang ditampilkan**:
  - Data pengaduan lengkap termasuk relasi user, kategori, dan status
- **View**: `Page.Pengaduan.Pengaduan-detail`

#### `edit(string $slug)`
- **Fungsi**: Menampilkan form edit pengaduan
- **Validasi**:
  - Hanya bisa diedit jika status = "Menunggu" (status_id = 1)
- **View**: `Page.Pengaduan.Pengaduan-edit`

#### `update(Request $request, string $slug)`
- **Fungsi**: Memperbarui data pengaduan
- **Validasi**: Sama dengan method store
- **Proses**:
  1. Handle update gambar (hapus yang lama jika ada)
  2. Update slug jika judul diubah
  3. Simpan perubahan data

#### `destroy(string $slug)`
- **Fungsi**: Menghapus pengaduan
- **Proses**:
  1. Hapus gambar terkait (jika ada)
  2. Hapus data dari database

### Fitur Tambahan
1. **Generate Nomor Pengaduan Unik**:
   - Format: `PENG-[5 digit random]-[3 digit random]-[tanggal]`
   - Contoh: `PENG-12345-678-20240315`

2. **Slug Otomatis**:
   - Dibuat dari judul pengaduan
   - Jika ada slug yang sama, akan ditambahkan angka di belakangnya

3. **Notifikasi Email**:
   - Menggunakan sistem queue
   - Dikirim ke admin dengan role sesuai kategori pengaduan

4. **Manajemen File**:
   - Gambar disimpan di folder `storage/app/public/pengaduan_images`
   - Otomatis dihapus ketika pengaduan dihapus/diupdate

### Catatan Penggunaan
1. Pastikan sudah mengkonfigurasi:
   - Queue worker untuk email
   - Storage link untuk akses file
   - Spatie Permission untuk manajemen role

2. Environment yang dibutuhkan:
   ```env
   QUEUE_CONNECTION=database
   ```

3. Tabel yang diperlukan:
   - pengaduans
   - kategori_pengaduan
   - status_pengaduan
   - tanggapans
   - model_has_roles (dari spatie permission)

### Flow Penggunaan
1. User membuat pengaduan melalui form
2. Sistem generate nomor unik dan simpan data
3. Buat tanggapan otomatis
4. Kirim notifikasi ke admin terkait
5. Admin dapat menanggapi melalui sistem
6. User dapat melacak status pengaduan