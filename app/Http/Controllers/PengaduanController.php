<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\User;
use App\Jobs\SendPengaduanEmail;
use App\Models\KategoriPengaduan;
use App\Models\StatusPengaduan;
use App\Models\Tanggapan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

        return view('Page.Pengaduan.Pengaduan-mahasiswa-create', compact('kategoris', 'statuses'));
    }
    // Menyimpan pengaduan ke database
    public function store(Request $request)
    {
        $request->validate([
            'judul_pengaduan' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_pengaduan,id',
            'isi_laporan' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);
    
        return DB::transaction(function () use ($request) {
            try {
                // Generate unique complaint number with lock to prevent race condition
                $latestId = Pengaduan::lockForUpdate()->max('id') ?? 0;
                $no_pengaduan = 'PENG-' . 
                              str_pad($latestId + 1, 5, '0', STR_PAD_LEFT) . '-' . 
                              Carbon::now()->format('Ymd');
    
                // Handle image upload
                $imagePath = null;
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('pengaduan_images', 'public');
                    
                    // Validate image was actually uploaded
                    if (!$imagePath) {
                        throw new \Exception('Gagal mengunggah gambar');
                    }
                }
    
                // Generate unique slug
                $slug = $this->generateUniqueSlug($request->judul_pengaduan);
    
                // Get category with lock to ensure consistency
                $kategori = KategoriPengaduan::lockForUpdate()->findOrFail($request->kategori_id);
    
                // Save complaint
                $pengaduan = Pengaduan::create([
                    'no_pengaduan' => $no_pengaduan,
                    'judul_pengaduan' => $request->judul_pengaduan,
                    'slug' => $slug,
                    'user_id' => Auth::id(),
                    'kategori_id' => $request->kategori_id,
                    'isi_laporan' => $request->isi_laporan,
                    'image' => $imagePath,
                    'status_id' => 1, // Status awal
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                // Create default response
                Tanggapan::create([
                    'pengaduan_id' => $pengaduan->id,
                    'isi_tanggapan' => 'Terima kasih telah mengirimkan Laporan pengaduan *(Ini adalah pesan otomatis)',
                    'status_id' => 1,
                    'user_id' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                // Prepare email data
                $emailData = [
                    'no_pengaduan' => $no_pengaduan,
                    'judul_pengaduan' => $request->judul_pengaduan,
                    'nama_pengadu' => Auth::user()->name,
                    'tanggal_pengaduan' => now()->format('d-m-Y H:i:s'),
                    'kategori' => $kategori->name,
                    'isi_laporan' => $pengaduan->isi_laporan,
                ];
    
                // Get recipients - outside transaction to minimize lock time
                $recipients = User::role($kategori->name)
                    ->orWhere(function($q) {
                        $q->role(['super_admin', 'admin']);
                    })
                    ->whereNotNull('email')
                    ->get()
                    ->unique('id');
    
                // Dispatch email jobs after transaction commits
                DB::afterCommit(function () use ($recipients, $emailData) {
                    foreach ($recipients as $recipient) {
                        SendPengaduanEmail::dispatch(
                            $recipient->email,
                            array_merge($emailData, ['admin_name' => $recipient->name])
                        )->onQueue('emails');
                    }
                });
    
                return redirect()->route('pengaduan.create')
                    ->with('success', 'Pengaduan berhasil dikirim!');
    
            } catch (\Exception $e) {
                Log::error('Error saving complaint: ' . $e->getMessage());
                
                // Delete uploaded image if transaction fails
                if (!empty($imagePath) && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                
                return redirect()->route('pengaduan.create')
                    ->with('error', 'Terjadi kesalahan. Silakan coba lagi.')
                    ->withInput();
            }
        });
    }
    
    /**
     * Generate unique slug with retry logic
     */
    protected function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;
    
        while (Pengaduan::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
    
        return $slug;
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
        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dihapus!');
    }
}