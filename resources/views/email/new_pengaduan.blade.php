<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pengaduan Baru</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #4a5568;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
            background-color: #ffffff;
        }
        .section-title {
            color: #2d3748;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 25px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
        }
        .detail-card {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #e2e8f0;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: 500;
            color: #4a5568;
            min-width: 120px;
        }
        .detail-value {
            color: #2d3748;
            flex-grow: 1;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 500;
            background-color: #e0e7ff;
            color: #4f46e5;
        }
        .attachment-container {
            margin-top: 10px;
        }
        .attachment-item {
            display: flex;
            align-items: center;
            padding: 8px;
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-top: 8px;
        }
        .attachment-icon {
            margin-right: 10px;
            color: #4f46e5;
        }
        .attachment-link {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }
        .footer {
            padding: 20px;
            text-align: center;
            color: #718096;
            font-size: 14px;
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Pengaduan Baru Diterima</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $data['admin_name'] }}</strong>,</p>
            <p>Sebuah pengaduan baru telah diterima dari {{ $data['nama_pengadu'] }}:</p>
            
            <div class="divider"></div>
            
            <!-- Pengaduan Details -->
            <div class="section-title">
                <span>📄 Detail Pengaduan</span>
            </div>
            
            <div class="detail-card">
                <div class="detail-row">
                    <div class="detail-label">Nomor Pengaduan</div>
                    <div class="detail-value">{{ $data['no_pengaduan'] }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Judul</div>
                    <div class="detail-value">{{ $data['judul_pengaduan'] }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Kategori</div>
                    <div class="detail-value">{{ $data['kategori'] }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="status-badge">Baru</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Tanggal</div>
                    <div class="detail-value">{{ $data['tanggal_pengaduan'] }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Isi Laporan</div>
                    <div class="detail-value">{{ $data['isi_laporan'] }}</div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Segera tanggapi pengaduan ini untuk memberikan respon terbaik kepada pelapor.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>