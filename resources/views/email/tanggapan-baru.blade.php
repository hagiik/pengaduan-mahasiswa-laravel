<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tanggapan Pengaduan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #334bd6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #334bd6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #6b7280;
            text-align: center;
        }
        .details {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Update Pengaduan Anda</h1>
    </div>
    
    <div class="content">
        <p>Halo {{ $user->name }},</p>
        
        <p>Pengaduan Anda dengan judul <strong>"{{ $tanggapan->pengaduan->judul_pengaduan }}"</strong> telah mendapatkan tanggapan baru:</p>
        
        <div class="details">
            <h3>Detail Tanggapan</h3>
            <p><strong>Status:</strong> <span style="color: #4f46e5;">{{ $tanggapan->status->status }}</span></p>
            <p><strong>Tanggapan:</strong></p>
            <p>{{ $tanggapan->isi_tanggapan }}</p>
            
            @if($tanggapan->gambar_tanggapan)
            <p>
                <strong>Lampiran:</strong><br>
                <img src="{{ asset('storage/' . $tanggapan->gambar_tanggapan) }}" alt="Lampiran tanggapan" style="max-width: 100%; border: 1px solid #e5e7eb; border-radius: 5px; margin-top: 10px;">
            </p>
            @endif
        </div>
        
        <a href="{{ route('pengaduan.show', $tanggapan->pengaduan->slug) }}" class="button">
            Lihat Detail Pengaduan
        </a>
        
        <div class="footer">
            <p>Terima kasih telah menggunakan layanan kami.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>