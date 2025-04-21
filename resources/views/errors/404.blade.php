@extends('errors::minimal')

@section('title', 'Halaman Tidak Ditemukan')
{{-- @section('code', '404') --}}
@section('message')
    <div style="text-align: center; padding: 50px;">
        <h1 style="font-size: 72px; margin-bottom: 0;">404</h1>
        <p style="font-size: 24px; color: #555;">Oops! Halaman yang kamu cari tidak ditemukan.</p>
        <p style="font-size: 16px; color: #777;">Mungkin halaman sudah dipindahkan atau link yang kamu akses salah.</p>
        <a href="{{ url('/') }}" 
           style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
           Kembali ke Beranda
        </a>
    </div>
@endsection
