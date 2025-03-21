<x-layouts.app :title="__('Edit Pengaduan')">
@include('partials.pengaduan')

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden">
            <x-slot name="header">
                <h2 class="text-xl font-semibold text-gray-800 mb-44">
                    Edit Pengaduan Mahasiswa
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
            @if(session('error'))
                <div x-data="{ visible: true }" x-show="visible" x-collapse>
                    <div x-show="visible" x-transition>
                        <flux:callout icon="exclamation-triangle" variant="destructive">
                            <flux:callout.heading>Error</flux:callout.heading>
                            <flux:callout.text>{{ session('error') }}</flux:callout.text>

                            <x-slot name="controls">
                                <flux:button icon="x-mark" variant="ghost" x-on:click="visible = false" />
                            </x-slot>
                        </flux:callout>
                    </div>
                </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6  border-b border-gray-200">
                            <!-- Form Edit Pengaduan -->
                            <form action="{{ route('pengaduan.update', $pengaduan->slug) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                            
                                <flux:field class="mb-4">
                                    <flux:label >Judul Pengaduan</flux:label>
                                    <flux:input name="judul_pengaduan" type="text" value="{{ old('judul_pengaduan', $pengaduan->judul_pengaduan) }}"  />
                                    @error('judul_pengaduan')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </flux:field>

                                <!-- Kategori Pengaduan -->
                                <flux:field class="mb-4">
                                    <flux:label  for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori Pengaduan</flux:label>
                                    <flux:select name="kategori_id" id="kategori_id"
                                         >
                                        @foreach($kategoris as $kategori)
                                            <flux:select.option value="{{ $kategori->id }}">{{ $kategori->name }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                    @error('kategori_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </flux:field>

                                <!-- Isi Laporan -->
                                <flux:field class="mb-4">
                                    <flux:label >Catatan Laporan</flux:label>
                                    <flux:textarea name="isi_laporan" id="isi_laporan" rows="5" >
                                        {{ old('isi_laporan', $pengaduan->isi_laporan) }}
                                    </flux:textarea>
                                    @error('isi_laporan')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </flux:field>

                                <!-- Upload Gambar -->
                                <flux:field class="mb-4">
                                    <flux:label>Upload Gambar </flux:label>
                                    <flux:input type="file" name="image" id="image" />
                                    @error('image')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    @if($pengaduan->image)
                                        <div class="mt-2">
                                            <p class="text-sm">Gambar Saat Ini:</p>
                                            <div class="mt-2 rounded-lg shadow-md overflow-hidden max-w-xs">
                                                <img src="{{ asset('storage/' . $pengaduan->image) }}" alt="Gambar Pengaduan" class="w-full h-48 object-cover">
                                            </div>
                                        </div>
                                    @endif
                                </flux:field>

                                <!-- Tombol Simpan -->
                                <flux:field class="mt-6">
                                    <flux:button type="submit" variant="primary" class="w-full">
                                        Simpan Perubahan
                                    </flux:button>
                                </flux:field>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>