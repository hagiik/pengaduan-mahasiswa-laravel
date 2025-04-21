<x-layouts.app :title="__('Buat Pengaduan | Pengaduan Mahasiswa')">
@include('partials.pengaduan')
    
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden">
            <x-slot name="header">
                <h2 class="text-xl font-semibold text-gray-800 mb-44">
                    Form Pengaduan Mahasiswa
                </h2>
            </x-slot>
            @if(session('success'))
                <div x-data="{ visible: true }" x-show="visible" x-collapse class="py-4">
                    <div x-show="visible" x-transition>
                        <flux:callout icon="archive-box" variant="secondary" color="blue">
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
                    <div class="overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b-1 border-gray-100">
                            
        
                            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <flux:field class="mb-4">
                                    <flux:label badge="Required">Judul Laporan Pengaduan</flux:label>
                                    <flux:input name="judul_pengaduan"  autofocus type="text" required clearable />
                                </flux:field>
        
                                <flux:field class="mb-4">
                                    <flux:label class="dark:text-gray-800" badge="Required">Kategori Pengaduan</flux:label>
                                    <flux:select name="kategori_id" searchable placeholder="Pilih Kategori...">
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
        
                                <flux:field class="mb-4">
                                    <flux:label badge="Required">Catatan Laporan</flux:label>
                                    <flux:textarea name="isi_laporan" placeholder="Isi laporan pengaduan..." required clearable />
                                </flux:field>
        
                                <flux:field class="mb-4">
                                    <flux:label badge="Optional">Upload Gambar</flux:label>
                                    <flux:input type="file" name="image" />
                                </flux:field>

                                <flux:field class="mb-4">
                                    <button 
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    class="w-full text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                >
                                    <span wire:loading.remove>
                                        Kirim Pengaduan
                                    </span>
                                    <span wire:loading>
                                        Mengirim...
                                    </span>
                                </button>    
                                </flux:field>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>