<div class="flex flex-col min-h-screen">
    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6 mt-24">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Cari Pengaduan</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <label for="no_pengaduan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Pengaduan</label>
                        <input type="text" id="no_pengaduan" wire:model="no_pengaduan" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600">
                        @error('no_pengaduan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" id="email" wire:model="email" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-1 flex items-end">
                        <button wire:click="search" 
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Cari
                        </button>
                    </div>
                </div>

                @if (session()->has('error'))
                    <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-md">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Hasil Pengaduan -->
            @if ($pengaduan)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detail Pengaduan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nomor Pengaduan</p>
                            <p class="font-medium">{{ $pengaduan->no_pengaduan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Judul Pengaduan</p>
                            <p class="font-medium">{{ $pengaduan->judul_pengaduan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal</p>
                            <p class="font-medium">{{ $pengaduan->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                            <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-semibold
                            @if($pengaduan->status->status == 'Menunggu')
                                bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300
                            @elseif($pengaduan->status->status == 'Diterima')
                                bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-200
                            @elseif($pengaduan->status->status == 'Diproses')
                                bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($pengaduan->status->status == 'Selesai')
                                bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-200
                            @elseif($pengaduan->status->status == 'Ditolak')
                                bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-200
                            @endif">
                            {{ $pengaduan->status->status }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>