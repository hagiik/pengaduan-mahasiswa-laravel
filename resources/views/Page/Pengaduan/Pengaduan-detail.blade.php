<x-layouts.app :title="__('Detail Pengaduan')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden">
            <x-slot name="header">
                <h2 class="text-xl font-semibold text-gray-800">
                    Detail Pengaduan
                </h2>
            </x-slot>
            @if(session('success'))
                <div x-data="{ visible: true }" x-show="visible" x-collapse>
                    <div x-show="visible" x-transition>
                        <flux:callout icon="archive-box" variant="secondary">
                            <flux:callout.heading>Status Pengaduan</flux:callout.heading>
                            <flux:callout.text>{{ session('success') }}.</flux:callout.text>
                
                            <x-slot name="controls">
                                <flux:button icon="x-mark" variant="ghost" x-on:click="visible = false" />
                            </x-slot>
                        </flux:callout>
                    </div>
                </div>
            @endif
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6  border-b border-gray-200">
                           <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <flux:input :label="__('Judul Laporan Pengaduan')" value="{{ $pengaduan->judul_pengaduan }}" readonly/>
                                <flux:input icon="identification" :label="__('Nomor Laporan Pengaduan')" value="{{ $pengaduan->no_pengaduan }}" readonly copyable />
                                <flux:field>
                                    <flux:label>Status</flux:label>
                                    <flux:badge color="{{ 
                                                $pengaduan->status->status == 'Menunggu' ? 'zinc' : 
                                                ($pengaduan->status->status == 'Diproses' ? 'yellow' : 
                                                ($pengaduan->status->status == 'Selesai' ? 'green' : 
                                                ($pengaduan->status->status == 'Ditolak' ? 'red' : '')))
                                            }}" 
                                            icon="{{ 
                                                $pengaduan->status->status == 'Menunggu' ? 'clock' : 
                                                ($pengaduan->status->status == 'Diproses' ? 'cube-transparent' : 
                                                ($pengaduan->status->status == 'Selesai' ? 'sparkles' : 
                                                ($pengaduan->status->status == 'Ditolak' ? 'x-circle' : '')))
                                            }}"
                                            class="inline-flex items-center px-2 py-2 text-sm rounded-full ">
                                            {{ $pengaduan->status->status }}
                                    </flux:badge>
                                </flux:field>

                                <flux:input icon="user" :label="__('Kategori Laporan Pengaduan')" value="{{ $pengaduan->kategori->name }}" readonly />
                                
                                <flux:field>
                                    <flux:label>Tanggal Laporan Pengaduan</flux:label>
                                    <flux:input value="{{ $pengaduan->created_at->format('d M Y H:i') }}" readonly/>
                                </flux:field>
                                <flux:field>
                                    <flux:label>Isi Laporan Pengaduan</flux:label>
                                    <flux:textarea
                                        icon="calendar-days"
                                        placeholder="{{ $pengaduan->isi_laporan }}"
                                        readonly
                                    />
                                </flux:field>
                           </div>
                           @if($pengaduan->image)
                           <div class="mt-2">
                               <p class="text-sm">Gambar Saat Ini:</p>
                               <div class="mt-2 rounded-lg shadow-md overflow-hidden max-w-xs">
                                   <img src="{{ asset('storage/' . $pengaduan->image) }}" alt="Gambar Pengaduan" class="w-full h-48 object-cover">
                               </div>
                           </div>
                       @endif

                            <!-- Tombol Kembali -->
                            <flux:field class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <flux:button icon="arrow-left-start-on-rectangle" variant="filled" href="{{ route('pengaduan.index') }}" wire:navigate>
                                    Kembali ke Daftar Pengaduan
                                </flux:button>
                                @if($pengaduan->status_id == 1) <!-- Cek apakah status adalah "Menunggu" -->
                                    <flux:button icon="pencil-square" variant="primary" href="{{ route('pengaduan.edit', $pengaduan->slug) }}" wire:navigate>
                                        Edit Pengaduan
                                    </flux:button>
                                @else
                                    <flux:tooltip content="Tidak dapat melakukan proses Edit karena pengaduan sudah diterima">
                                            <flux:button disabled icon="pencil-square" class="w-full" variant="primary">Edit Pengaduan</flux:button>
                                    </flux:tooltip>
                                @endif
                            </flux:field>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>