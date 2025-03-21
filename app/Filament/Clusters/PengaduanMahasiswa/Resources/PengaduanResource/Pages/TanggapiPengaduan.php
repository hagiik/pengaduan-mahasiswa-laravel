<?php

namespace App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanResource\Pages;

use App\Models\Pengaduan;
use App\Models\Tanggapan;
use App\Models\StatusPengaduan;
use App\Filament\Clusters\PengaduanMahasiswa\Resources\PengaduanResource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class TanggapiPengaduan extends Page
{
    protected static string $resource = PengaduanResource::class;
    protected static string $view = 'filament.clusters.pengaduan-mahasiswa.resources.pengaduan-resource.pages.tanggapi-pengaduan';

    public Pengaduan $pengaduan;
    public array $tanggapans = [];
    public ?array $data = [];

    public function mount($record): void
    {
        $this->pengaduan = Pengaduan::with('status')->findOrFail($record);

        if (!$this->pengaduan->status) {
            abort(404, 'Status pengaduan tidak ditemukan.');
        }

        if ($this->pengaduan->status->status === 'Selesai') {
            abort(403, 'Tidak dapat menanggapi pengaduan yang telah selesai.');
        }

        // Ambil daftar tanggapan untuk ditampilkan dalam Blade
        $this->tanggapans = Tanggapan::where('pengaduan_id', $this->pengaduan->id)
            ->with('penanggap', 'status')
            ->latest()
            ->get()
            ->toArray();

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('isi_tanggapan')
                    ->label('Isi Tanggapan')
                    ->required(),
                FileUpload::make('gambar_tanggapan')
                    ->label('Gambar Tanggapan')
                    ->image()
                    ->directory('tanggapan-images')
                    ->visibility('public')
                    ->nullable(),
                
                Select::make('status_id')
                    ->label('Status')
                    ->options(StatusPengaduan::all()->pluck('status', 'id'))
                    ->searchable()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $this->form->validate();

        $status = StatusPengaduan::find($this->data['status_id']);
        if (!$status) {
            abort(404, 'Status pengaduan tidak ditemukan.');
        }
        // dd($this->data);

        Tanggapan::create([
            'pengaduan_id' => $this->pengaduan->id,
            'isi_tanggapan' => $this->data['isi_tanggapan'],
            'status_id' => $this->data['status_id'],
            'penanggap_id' => Auth::id(),
            'user_id' => $this->pengaduan->user_id,
            'gambar_tanggapan' => empty($this->data['gambar_tanggapan']) ? null : (is_array($this->data['gambar_tanggapan']) ? implode(',', $this->data['gambar_tanggapan']) : $this->data['gambar_tanggapan']),
        ]);
        

        $this->pengaduan->update([
            'status_id' => $this->data['status_id'],
        ]);

        $this->redirect($this->getResource()::getUrl('index'));
    }
}
