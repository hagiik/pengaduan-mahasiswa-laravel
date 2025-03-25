<x-filament::page>
    <!-- Menampilkan Detail Pengaduan -->
    <div class="p-6 rounded-lg shadow">
        <h3 class="text-xl font-semibold mb-4">Detail Pengaduan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-medium">Nomor Pengaduan:</label>
                <p class="border px-4 py-2 rounded-full ">{{ $pengaduan->no_pengaduan }}</p>
            </div>
            <div>
                <label class="font-medium">Nama Pelapor:</label>
                <p class="border px-4 py-2 rounded-full ">{{ $pengaduan->user->name }}</p>
            </div>
            <div>
                <label class="font-medium">Judul Pengaduan:</label>
                <p class="border px-4 py-2 rounded-full ">{{ $pengaduan->judul_pengaduan }}</p>
            </div>
            <div class="col-span-2">
                <label class="font-medium">Isi Laporan:</label>
                <p class="border px-4 py-2 rounded-full ">{{ $pengaduan->isi_laporan }}</p>
            </div>
            <div>
                <label class="font-medium">Kategori:</label>
                <p class="border px-4 py-2 rounded-full ">{{ $pengaduan->kategori->name ?? '-' }}</p>
            </div>
            <div>
                <label class="font-medium">Status:</label>
                <p class="border px-4 py-2 rounded-full ">{{ $pengaduan->status->status ?? '-' }}</p>
            </div>
            <div class="col-span-2">
                <label class="font-medium">Gambar:</label>
                @if ($pengaduan->image)
                    <img src="{{ asset('storage/' . $pengaduan->image) }}" class="mt-2 w-32 h-32 object-cover rounded-lg">
                @else
                    <p class="border px-4 py-2 rounded-full ">Tidak ada gambar</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Form Tanggapan -->
    <form wire:submit.prevent="submit" class="mt-6">
        {{ $this->form }}

        <x-filament::button type="submit" class="bg-primary-600 mt-6">
            Kirim Tanggapan
        </x-filament::button>
    </form>

    <!-- Tampilkan tabel tanggapan -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold">Daftar Tanggapan</h3>
        <div class="overflow-x-auto mt-4">
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
                    @foreach ($this->tanggapans as $tanggapan)
                        <tr>
                            <td class="border px-4 py-2">{{ $tanggapan['penanggap']['name'] ?? 'Tidak diketahui' }}</td>
                            <td class="border px-4 py-2">{{ Str::limit($tanggapan['isi_tanggapan'], 50) }}</td>
                            <td class="border px-4 py-2">
                                <span class="px-2 py-1 rounded">
                                    {{ $tanggapan['status']['status'] ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="border px-4 py-2">
                                @if ($tanggapan['gambar_tanggapan'])
                                    <img src="{{ asset('storage/' . $tanggapan['gambar_tanggapan']) }}" class="w-16 h-16 object-cover" alt="Gambar Tanggapan">
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($tanggapan['created_at'])->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament::page>
