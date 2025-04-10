<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {

    public function mount(): void
    {
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(route('pengaduan.index', absolute: false), navigate: true);
        }
    }

    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(route('pengaduan.index', absolute: false), navigate: true);
            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
};
?>


<div class="mt-4 flex flex-col gap-6">
    <flux:text class="text-center">
        {{ __('Verifikasi email Anda melalui tautan yang telah kami kirim. Periksa folder spam jika tidak menemukannya.') }}
    </flux:text>

    @if (session('status') == 'verification-link-sent')
        <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
            {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat membuat akun.') }}
        </flux:text>
    @endif

    <div class="flex flex-col items-center justify-between space-y-3">
        <flux:button wire:click="sendVerification" variant="primary" class="w-full">
            {{ __('Kirim ulang email verifikasi') }}
        </flux:button>

        <flux:link class="text-sm cursor-pointer" wire:click="logout">
            {{ __('Keluar') }}
        </flux:link>
    </div>
</div>
