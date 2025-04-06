<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pengaduan;
use App\Models\User;
use App\Models\Tanggapan;

class SearchPengaduan extends Component
{
    public $no_pengaduan = '';
    public $email = '';
    public $pengaduan = null;
    public $tanggapans = [];

    protected $rules = [
        'no_pengaduan' => 'required|string',
        'email' => 'required|email'
    ];

    public function search()
    {
        $this->validate();
        $this->reset(['pengaduan', 'tanggapans']);

        // Cari user berdasarkan email
        $user = User::where('email', $this->email)->first();

        if ($user) {
            // Cari pengaduan dengan eager loading relasi tanggapan
            $this->pengaduan = Pengaduan::with(['tanggapan' => function($query) {
                $query->with(['penanggap', 'status'])
                      ->orderBy('created_at', 'desc');
            }])
            ->where('no_pengaduan', $this->no_pengaduan)
            ->where('user_id', $user->id)
            ->first();

            if ($this->pengaduan) {
                $this->tanggapans = $this->pengaduan->tanggapan;
            }
        }

        if (!$this->pengaduan) {
            session()->flash('error', 'Pengaduan tidak ditemukan!');
        }
    }

    public function render()
    {
        return view('livewire.search-pengaduan');
    }
}