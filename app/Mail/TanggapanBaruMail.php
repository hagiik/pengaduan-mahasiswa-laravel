<?php

namespace App\Mail;

use App\Models\Tanggapan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TanggapanBaruMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tanggapan;

    public function __construct(Tanggapan $tanggapan)
    {
        $this->tanggapan = $tanggapan;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Update Pengaduan: ' . $this->tanggapan->pengaduan->judul_pengaduan,
        );
    }

    public function content()
    {
        return new Content(
            view: 'email.tanggapan-baru',
            with: [
                'tanggapan' => $this->tanggapan,
                'user' => $this->tanggapan->pengaduan->user,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}