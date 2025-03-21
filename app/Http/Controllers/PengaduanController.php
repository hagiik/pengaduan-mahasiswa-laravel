<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\KategoriPengaduan;
use App\Models\StatusPengaduan;
use App\Models\Tanggapan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PengaduanController extends Controller
{
    // Menampilkan form pengaduan
    

    // Menampilkan daftar pengaduan berdasarkan ID pengguna yang sedang login
    public function index()
    {
        // Ambil ID pengguna yang sedang login
        $userId = Auth::id();

        // Ambil daftar pengaduan berdasarkan user_id
        $pengaduans = Pengaduan::where('user_id', $userId)
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal terbaru
            ->paginate(10); // Paginasi 10 item per halaman

        return view('Page.Pengaduan.Pengaduan-mahasiswa-list', compact('pengaduans'));
    }

    public function create()
    {
        // Ambil data kategori dan status untuk dropdown
        $kategoris = KategoriPengaduan::all();
        $statuses = StatusPengaduan::all();

        return view('Page.Pengaduan.Pengaduan-mahasiswa', compact('kategoris', 'statuses'));
    }
    // Menyimpan pengaduan ke database
    public function store(Request $request)
    {
        
        // Validasi input
        $request->validate([
            'judul_pengaduan' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_pengaduan,id',
            'isi_laporan' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        try {
            // Generate nomor pengaduan unik
            do {
                $random5 = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT); // 5 digit
                $random3 = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT); // 3 digit
                $tanggalSekarang = Carbon::now()->format('Ymd'); // Tanggal sekarang
                $no_pengaduan = 'PENG-' . $random5 . '-' . $random3 . '-' . $tanggalSekarang;
    
                // Periksa apakah nomor pengaduan sudah ada di database
                $exists = Pengaduan::where('no_pengaduan', $no_pengaduan)->exists();
            } while ($exists); // Ulangi jika nomor pengaduan sudah ada
    
            // Simpan gambar jika ada
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('pengaduan_images', 'public');
            }
    
            // Buat slug dari judul pengaduan
            $slug = Str::slug($request->judul_pengaduan);
    
            // Periksa apakah slug sudah ada
            $count = Pengaduan::where('slug', 'like', $slug . '%')->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
    
            // Simpan data pengaduan
            $pengaduan = Pengaduan::create([
                'no_pengaduan' => $no_pengaduan,
                'judul_pengaduan' => $request->judul_pengaduan,
                'slug' => $slug,
                'user_id' => Auth::id(),
                'kategori_id' => $request->kategori_id,
                'isi_laporan' => $request->isi_laporan,
                'image' => $imagePath,
                'status_id' => 1, // Default status (misalnya: 1 = "Menunggu")
            ]);
    
            // Simpan tanggapan default
            Tanggapan::create([
                'pengaduan_id' => $pengaduan->id,
                'isi_tanggapan' => 'Terima kasih telah mengirimkan pengaduan. Kami akan segera menindaklanjuti.',
                'status_id' => 1,
                'user_id' => Auth::id(),
            ]);
    
            // Redirect dengan pesan sukses
            return redirect()->route('pengaduan.create')->with('success', 'Pengaduan berhasil dikirim!');
        } catch (\Exception $e) {
            // Log error
            Log::error('Error saat menyimpan pengaduan: ' . $e->getMessage());
    
            // Redirect dengan pesan error
            return redirect()->route('pengaduan.create')->with('error', 'Terjadi kesalahan saat mengirim pengaduan. Silakan coba lagi.');
        }
    }

    // Menampilkan detail pengaduan berdasarkan slug
    public function show(string $slug)
    {
        $pengaduan = Pengaduan::with(['user', 'kategori', 'status'])
            ->where('slug', $slug)
            ->firstOrFail(); // Cari berdasarkan slug

        return view('Page.Pengaduan.Pengaduan-detail', compact('pengaduan'));
    }

    // Menampilkan form edit pengaduan

    public function edit(string $slug)
    {
        // Cari pengaduan berdasarkan slug
        $pengaduan = Pengaduan::where('slug', $slug)->firstOrFail();
    
        // Periksa status pengaduan
        if ($pengaduan->status_id != 1) { // Asumsikan status_id = 1 adalah "Menunggu"
            return redirect()->route('pengaduan.index')->with('error', 'Pengaduan tidak dapat diedit karena statusnya bukan "Menunggu".');
        }
    
        // Ambil data kategori dan status untuk dropdown
        $kategoris = KategoriPengaduan::all();
        $statuses = StatusPengaduan::all();
    
        // Tampilkan view edit
        return view('Page.Pengaduan.Pengaduan-edit', compact('pengaduan', 'kategoris', 'statuses'));
    }

    public function update(Request $request, string $slug)
    {
        // Validasi input
        $request->validate([
            'judul_pengaduan' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_pengaduan,id',
            'isi_laporan' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Hapus validasi untuk status_id
        ]);
    
        $pengaduan = Pengaduan::where('slug', $slug)->firstOrFail();
    
        // Simpan gambar jika ada
        $imagePath = $pengaduan->image;
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($pengaduan->image) {
                Storage::disk('public')->delete($pengaduan->image);
            }
    
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('pengaduan_images', 'public');
        }
    
        // Periksa apakah judul diubah
        if ($pengaduan->judul_pengaduan !== $request->judul_pengaduan) {
            $newSlug = Str::slug($request->judul_pengaduan);
    
            // Periksa apakah slug sudah ada
            $count = Pengaduan::where('slug', 'like', $slug . '%')->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
    
            $pengaduan->slug = $newSlug;
        }
    
        // Update data pengaduan
        $pengaduan->update([
            'judul_pengaduan' => $request->judul_pengaduan,
            'kategori_id' => $request->kategori_id,
            'isi_laporan' => $request->isi_laporan,
            'image' => $imagePath,
            'status_id' => $pengaduan->status_id, // Gunakan nilai status_id yang sudah ada
        ]);
    
        // Redirect dengan pesan sukses
        return redirect()->route('pengaduan.show', $pengaduan->slug)->with('success', 'Pengaduan berhasil diperbarui!');
    }

    // Menghapus pengaduan dari database
    public function destroy(string $slug)
    {
        $pengaduan = Pengaduan::where('slug', $slug)->firstOrFail(); // Cari berdasarkan slug

        // Hapus gambar jika ada
        if ($pengaduan->image) {
            Storage::disk('public')->delete($pengaduan->image);
        }

        // Hapus data pengaduan
        $pengaduan->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('pengaduan.list')->with('success', 'Pengaduan berhasil dihapus!');
    }
}