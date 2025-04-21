<x-layouts.app :title="__('Daftar Pengaduan Saya | Pengaduan Mahasiswa')">
    @include('partials.pengaduan')
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden">
            <x-slot name="header">
                <h2 class="text-xl font-semibold text-gray-800">
                    Daftar Pengaduan Saya
                </h2>
            </x-slot>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-100 shadow-sm sm:rounded-sm">
                            @if($pengaduans->isEmpty())
                                <div class="alert alert-info text-center">
                                    Anda belum melakukan laporan pengaduan. klik <a href="{{route('pengaduan.create')}}" class="font-bold underline">Buat Pengaduan</a> untuk membuat pengaduan
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-center">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2 border">No. Pengaduan</th>
                                                <th class="px-4 py-2 border">Judul</th>
                                                <th class="px-4 py-2 border">Tanggal</th>
                                                <th class="px-4 py-2 border">Kategori</th>
                                                <th class="px-4 py-2 border">Status</th>
                                                <th class="px-4 py-2 border">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pengaduans as $pengaduan)
                                                <tr>
                                                    <flux:tooltip content="{{ $pengaduan->no_pengaduan }}" position="top">
                                                        <td class="px-4 py-2 border">{{ Str::limit($pengaduan->no_pengaduan, 10, '...') }}</td>
                                                    </flux:tooltip>
                                                    <td class="px-4 py-2 border">{{ $pengaduan->judul_pengaduan }}</td>
                                                    <td class="px-4 py-2 border">{{ $pengaduan->created_at->format('d M Y H:i') }}</td>
                                                    <td class="px-4 py-2 border">{{ $pengaduan->kategori->name }}</td>
                                                    <td class="px-4 py-2 border">
                                                        <flux:badge color="{{ 
                                                            $pengaduan->status->status == 'Menunggu' ? 'zinc' : 
                                                            ($pengaduan->status->status == 'Diterima' ? 'blue' : 
                                                            ($pengaduan->status->status == 'Diproses' ? 'yellow' : 
                                                            ($pengaduan->status->status == 'Selesai' ? 'green' : 
                                                            ($pengaduan->status->status == 'Ditolak' ? 'red' : ''))))
                                                        }}" class="px-2 py-1 text-sm rounded-full">
                                                            {{ $pengaduan->status->status }}
                                                        </flux:badge>
                                                    </td>
                                                    <td class="px-4 py-4 border">
                                                        <!-- Tombol Detail -->
                                                        <flux:button href="{{ route('pengaduan.show', $pengaduan->slug) }}" variant="primary" wire:navigate>Detail</flux:button>
                                                        @if($pengaduan->status_id == 1) 
                                                            <flux:button href="{{ route('pengaduan.edit', $pengaduan->slug) }}" variant="filled" wire:navigate>Edit</flux:button>
                                                            
                                                            <flux:modal.trigger name="delete-profile">
                                                                <flux:button variant="danger">Delete</flux:button>
                                                            </flux:modal.trigger>
                                                            
                                                            <flux:modal name="delete-profile" class="min-w-[22rem]">
                                                                <div class="space-y-6">
                                                                    <div>
                                                                        <flux:heading size="lg">Hapus Pengaduan?</flux:heading>
                                                            
                                                                        <flux:subheading>
                                                                            <p>Anda akan menghapus Pengaduan ini.</p>
                                                                            <p>Tindakan ini tidak dapat dibatalkan.</p>
                                                                        </flux:subheading>
                                                                    </div>
                                                            
                                                                    <div class="flex gap-2">
                                                                        <flux:spacer />
                                                            
                                                                        <flux:modal.close>
                                                                            <flux:button variant="ghost">Cancel</flux:button>
                                                                        </flux:modal.close>
                                                                        <form action="{{ route('pengaduan.destroy', $pengaduan->slug) }}" method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <flux:button type="submit" variant="danger">
                                                                                Hapus
                                                                            </flux:button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </flux:modal>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4 flex justify-end">
                                    {{ $pengaduans->links() }}
                                </div>                                
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>