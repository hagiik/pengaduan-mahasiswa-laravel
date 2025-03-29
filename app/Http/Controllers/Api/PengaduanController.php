<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengaduanResource;
use App\Models\Pengaduan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    // API untuk mendapatkan daftar pengaduan
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => PengaduanResource::collection(Pengaduan::latest()->paginate(10))
        ], 200);
    }

    // API untuk mendapatkan detail pengaduan berdasarkan ID
    public function show($id): JsonResponse
    {
        $pengaduan = Pengaduan::with(['user', 'kategori', 'status'])->find($id);

        if (!$pengaduan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengaduan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new PengaduanResource($pengaduan)
        ], 200);
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'no_pengaduan' => 'required|unique:pengaduan,no_pengaduan',
            'judul_pengaduan' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'kategori_id' => 'required|exists:kategori_pengaduan,id',
            'isi_laporan' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_id' => 'required|exists:status_pengaduan,id',
        ]);

        // Jika validasi gagal, kirim respon error
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('pengaduan', 'public');
        }

        // Buat data pengaduan baru
        $pengaduan = Pengaduan::create([
            'no_pengaduan' => $request->no_pengaduan,
            'judul_pengaduan' => $request->judul_pengaduan,
            'slug' => Str::slug($request->judul_pengaduan),
            'user_id' => $request->user_id,
            'kategori_id' => $request->kategori_id,
            'isi_laporan' => $request->isi_laporan,
            'image' => $imagePath,
            'status_id' => $request->status_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengaduan berhasil ditambahkan',
            'data' => new PengaduanResource($pengaduan)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Validasi input (gunakan 'sometimes' agar bisa update sebagian)
        $validator = Validator::make($request->all(), [
            'judul_pengaduan' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'kategori_id' => 'nullable|exists:kategori_pengaduan,id',
            'isi_laporan' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_id' => 'nullable|exists:status_pengaduan,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        // Cari data pengaduan berdasarkan ID
        $pengaduan = Pengaduan::find($id);
        if (!$pengaduan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengaduan tidak ditemukan'
            ], 404);
        }
    
        // Jika ada gambar baru, hapus gambar lama dan simpan yang baru
        if ($request->hasFile('image')) {
            if ($pengaduan->image) {
                Storage::disk('public')->delete($pengaduan->image);
            }
            $imagePath = $request->file('image')->store('pengaduan', 'public');
            $pengaduan->image = $imagePath;
        }
    
        // Perbarui data yang dikirim (gunakan `only()` agar hanya yang dikirim diperbarui)
        $updateData = $request->only([
            'judul_pengaduan', 'user_id', 'kategori_id', 'isi_laporan', 'status_id'
        ]);
    
        // Jika ada judul baru, update juga slug
        if ($request->has('judul_pengaduan')) {
            $updateData['slug'] = Str::slug($request->judul_pengaduan);
        }
    
        $pengaduan->update($updateData);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Pengaduan berhasil diperbarui',
            'data' => new PengaduanResource($pengaduan)
        ], 200);
    }
    
    public function destroy($id)
    {
        // Cari data pengaduan berdasarkan ID
        $pengaduan = Pengaduan::find($id);
    
        if (!$pengaduan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengaduan tidak ditemukan'
            ], 404);
        }
    
        // Jika ada gambar, hapus dari storage
        if ($pengaduan->image) {
            Storage::disk('public')->delete($pengaduan->image);
        }
    
        // Hapus data dari database
        $pengaduan->delete();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Pengaduan berhasil dihapus'
        ], 200);
    }
    
}