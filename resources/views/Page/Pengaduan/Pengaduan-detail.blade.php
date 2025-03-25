<x-layouts.app :title="__('Detail Pengaduan')">
@include('partials.pengaduan')

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

            {{-- Track status pengaduan --}}
            <section class="py-8 antialiased md:py-18">
                <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                        Lihat status pengaduan kamu #{{ $pengaduan->no_pengaduan }}
                    </h2>

                    <div class="mt-6 sm:mt-8 lg:flex lg:gap-8">
                        <div class="mt-6 grow sm:mt-8 lg:mt-0">
                            <div class="space-y-6 rounded-lg border border-gray-200  p-6 shadow-sm dark:border-gray-700 ">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Riwayat Tanggapan</h3>

                                <ol class="relative ms-3 border-s border-gray-200 dark:border-gray-700">
                                    @foreach($pengaduan->tanggapan as $tanggapan)
                                        <li class="mb-10 ms-6">
                                            <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full 
                                                @if($tanggapan->status->status == 'Menunggu')
                                                    bg-gray-100 text-gray-500
                                                @elseif($tanggapan->status->status == 'Diterima')
                                                    bg-blue-100 text-blue-500
                                                @elseif($tanggapan->status->status == 'Diproses')
                                                    bg-yellow-100 text-yellow-500
                                                @elseif($tanggapan->status->status == 'Selesai')
                                                    bg-green-100 text-green-500
                                                @elseif($tanggapan->status->status == 'Ditolak')
                                                    bg-red-100 text-red-500
                                                @endif
                                                ring-8 ring-white dark:ring-gray-800">
                                                @if($tanggapan->status->status == 'Menunggu')
                                                    <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                @elseif($tanggapan->status->status == 'Diterima')
                                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M10 12v1h4v-1m4 7H6a1 1 0 0 1-1-1V9h14v9a1 1 0 0 1-1 1ZM4 5h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                                                    </svg>
                                                @elseif($tanggapan->status->status == 'Diproses')
                                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19V6a1 1 0 0 1 1-1h4.032a1 1 0 0 1 .768.36l1.9 2.28a1 1 0 0 0 .768.36H16a1 1 0 0 1 1 1v1M3 19l3-8h15l-3 8H3Z"/>
                                                    </svg>
                                                @elseif($tanggapan->status->status == 'Selesai')
                                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.032 12 1.984 1.984 4.96-4.96m4.55 5.272.893-.893a1.984 1.984 0 0 0 0-2.806l-.893-.893a1.984 1.984 0 0 1-.581-1.403V7.04a1.984 1.984 0 0 0-1.984-1.984h-1.262a1.983 1.983 0 0 1-1.403-.581l-.893-.893a1.984 1.984 0 0 0-2.806 0l-.893.893a1.984 1.984 0 0 1-1.403.581H7.04A1.984 1.984 0 0 0 5.055 7.04v1.262c0 .527-.209 1.031-.581 1.403l-.893.893a1.984 1.984 0 0 0 0 2.806l.893.893c.372.372.581.876.581 1.403v1.262a1.984 1.984 0 0 0 1.984 1.984h1.262c.527 0 1.031.209 1.403.581l.893.893a1.984 1.984 0 0 0 2.806 0l.893-.893a1.985 1.985 0 0 1 1.403-.581h1.262a1.984 1.984 0 0 0 1.984-1.984V15.7c0-.527.209-1.031.581-1.403Z"/>
                                                    </svg>
                                                @elseif($tanggapan->status->status == 'Ditolak')
                                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m6 6 12 12m3-6a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                    </svg>
                                                @endif
                                            </span>
                                            <h4 class="mb-0.5 text-base font-semibold text-gray-900 dark:text-white">
                                                Tanggapan pada {{ $tanggapan->created_at->format('d M Y H:i') }}
                                            </h4>
                                            <p class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                                {{ $tanggapan->isi_tanggapan }}
                                            </p>
                                            @if($tanggapan->gambar_tanggapan)
                                                <div class="mt-2">
                                                    <p class="text-sm">Gambar Tanggapan:</p>
                                                    <div class="mt-2 rounded-lg shadow-md overflow-hidden max-w-xs">
                                                        <img src="{{ asset('storage/' . $tanggapan->gambar_tanggapan) }}" alt="Gambar Tanggapan" class="w-full h-48 object-cover">
                                                    </div>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</x-layouts.app>