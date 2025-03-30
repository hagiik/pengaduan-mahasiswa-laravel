<x-filament::page>
    <div class="p-6">
        <h3 class="text-xl font-semibold mb-4">Detail Pengaduan</h3>
        <div class="shadow-md rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="font-medium">Nomor Pengaduan:</label>
                    <p class="border px-4 py-2 rounded">{{ $pengaduan->no_pengaduan }}</p>
                </div>
                <div>
                    <label class="font-medium">Pelapor:</label>
                    <p class="border px-4 py-2 rounded">{{ $pengaduan->user->name }}</p>
                </div>
                <div>
                    <label class="font-medium">Judul Pengaduan:</label>
                    <p class="border px-4 py-2 rounded">{{ $pengaduan->judul_pengaduan }}</p>
                </div>
                <div>
                    <label class="font-medium">Isi Pengaduan:</label>
                    <p class="border px-4 py-2 rounded">{{ $pengaduan->isi_laporan }}</p>
                </div>
                <div>
                    <label class="font-medium">Status:</label>
                    <p class="border px-4 py-2 rounded">{{ $pengaduan->status->status }}</p>
                </div>
                <div>
                    <label class="font-medium">Tanggal:</label>
                    <p class="border px-4 py-2 rounded">{{ $pengaduan->created_at->format('d M Y, H:i') }}</p>
                </div>
                
                <!-- Gambar Pengaduan -->
                <div class="md:col-span-2">
                    <label class="font-medium">Gambar Pengaduan:</label>
                    <div class="border px-4 py-2 rounded flex justify-center">
                        @if (!empty($pengaduan->gambar))
                            <a href="{{ asset('storage/' . $pengaduan->gambar) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pengaduan->gambar) }}" class="w-32 h-32 object-cover rounded">
                            </a>
                        @else
                            <p class="text-gray-500">Tidak ada gambar</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Tanggapan -->
    <div class="p-6">
        <h3 class="text-xl font-semibold mb-4">Daftar Tanggapan</h3>
        <div class="shadow-md rounded-lg p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">Penanggap</th>
                            <th class="border px-4 py-2">Isi Tanggapan</th>
                            <th class="border px-4 py-2">Status</th>
                            <th class="border px-4 py-2">Gambar</th>
                            <th class="border px-4 py-2">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tanggapans as $tanggapan)
                            <tr class="hover:">
                                <td class="border px-4 py-2">{{ $tanggapan['penanggap']['name'] ?? 'Tidak diketahui' }}</td>
                                <td class="border px-4 py-2">{{ Str::limit($tanggapan['isi_tanggapan'], 50) }}</td>
                                <td class="border px-4 py-2">
                                    <span class="px-2 py-1 rounded bg-green-200 text-green-800">
                                        {{ $tanggapan['status']['status'] ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td class="border px-4 py-2 text-center">
                                    @if (!empty($tanggapan['gambar_tanggapan']))
                                        <a href="{{ asset('storage/' . $tanggapan['gambar_tanggapan']) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $tanggapan['gambar_tanggapan']) }}" class="w-16 h-16 object-cover rounded">
                                        </a>
                                    @else
                                        <span class="text-gray-500">Tidak ada gambar</span>
                                    @endif
                                </td>
                                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($tanggapan['created_at'])->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border px-4 py-2 text-center text-gray-500">Belum ada tanggapan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament::page>
