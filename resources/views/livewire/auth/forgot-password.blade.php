<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('Tautan untuk mengatur ulang Kata sandi, silahkan cek email kamu.'));
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Lupa kata sandi')" :description="__('Masukan kata alamat email kamu untuk mendapatkan link ganti kata sandi')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Alamat Email')"
            type="email"
            required
            autofocus
            placeholder="email@example.com"
        />

        <flux:button variant="primary" type="submit" class="w-full">{{ __('Atur Ulang Kata Sandi') }}</flux:button>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-400">
        {{ __('atau, kembali ke halaman') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Masuk') }}</flux:link>
    </div>
</div>
