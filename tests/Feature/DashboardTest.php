<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    // Tambahkan is_active = 1
    $user = User::factory()->create(['is_active' => 1]);
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});

test('inactive users are redirected with message', function () {
    $user = User::factory()->create(['is_active' => 0]);
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    
    // Cek redirect ke login
    $response->assertRedirect(route('login'));
    
    // Cek session errors (karena Anda menggunakan withErrors())
    $response->assertSessionHasErrors([
        'error' => 'Akun Anda telah dinonaktifkan oleh admin.'
    ]);
    
    // Verifikasi user benar-benar di-logout
    $this->assertGuest();
});