@extends('errors::minimal')

@section('title', 'Website Sedang Maintenance')
{{-- @section('code', '503') --}}
@section('message')
    <div style="text-align: center; padding: 50px;">
        <img src="{{ asset('images/maintenance.svg') }}" alt="Maintenance" style="max-width: 300px; margin-bottom: 20px;">
        <h1 style="font-size: 48px; margin-bottom: 10px;">Mohon Maaf!</h1>
        <p style="font-size: 20px; color: #555;">Website kami sedang dalam proses perawatan atau pembaruan.</p>
        <p style="font-size: 16px; color: #777;">Silakan coba kembali beberapa saat lagi ya!</p>
    </div>
@endsection
