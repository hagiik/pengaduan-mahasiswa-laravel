@extends('errors::minimal')

@section('title', 'Akses Ditolak')
{{-- @section('code', '403') --}}
@section('message')
    <div style="text-align: center; padding: 50px;">
        <img src="{{ asset('images/cancle.svg') }}" alt="Access Denied" style="max-width: 300px; margin-bottom: 20px;">
        <h1 style="font-size: 48px; margin-bottom: 10px;">403 - Akses Ditolak</h1>
        <p style="font-size: 20px; color: #555;">
            Oops! Kamu tidak memiliki izin untuk mengakses halaman ini.
        </p>
        <p style="font-size: 16px; color: #777;">
            Jika menurutmu ini adalah kesalahan, silakan hubungi admin untuk bantuan.
        </p>
        <a href="{{ url('/') }}" 
           style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
           Kembali ke Beranda
        </a>
    </div>
@endsection
