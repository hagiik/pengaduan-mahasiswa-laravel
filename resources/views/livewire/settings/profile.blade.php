<?php

use App\Models\User;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $nimd = '';
    public int|string|null $fakultas_id = null;
    public int|string|null $prodi_id = null;
    public string $telepon = '';

    public array $fakultasList = [];
    public array $prodiList = [];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->nimd = $user->nimd ?? '';
        $this->telepon = $user->telepon ?? '';
        $this->fakultas_id = $user->fakultas_id;
        $this->prodi_id = $user->prodi_id;

        $this->fakultasList = Fakultas::all()->toArray();
        $this->prodiList = Prodi::all()->toArray();
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'nimd' => ['nullable', 'string', 'max:20', Rule::unique(User::class)->ignore($user->id)],
            'telepon' => ['nullable', 'string', 'max:15', Rule::unique(User::class)->ignore($user->id)],
            'fakultas_id' => ['nullable', 'exists:fakultas,id'],
            'prodi_id' => ['nullable', 'exists:prodi,id'],
        ]);


        $validated['nimd'] = $validated['nimd'] ?: null;
        $validated['telepon'] = $validated['telepon'] ?: null;

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Tambahkan session flash message
        Session::flash('status', 'success');
        Session::flash('message', 'Profil berhasil diperbarui!');

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
};
?>

<section class="w-full">
    @include('partials.settings-heading')
    <x-session-alert />
    <x-settings.layout :heading="__('Profile')" :subheading="__('Perbarui nama dan alamat email')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Nama')" type="text" required autofocus autocomplete="name" />
            <flux:input wire:model="nimd" :label="__('NIM/NID')" type="number" autocomplete="nimd" />

            <flux:field class="mb-4">
                <flux:label>Fakultas</flux:label>
                <flux:select wire:model="fakultas_id" searchable >
                    <option value="">Pilih Fakultas...</option>
                    @foreach($fakultasList as $fakultas)
                        <option value="{{ $fakultas['id'] }}">{{ $fakultas['name'] }}</option>
                    @endforeach
                </flux:select>
            </flux:field>
            
            <flux:field class="mb-4">
                <flux:label>Program Studi</flux:label>
                <flux:select wire:model="prodi_id" searchable >
                    <option value="">Pilih Prodi...</option>
                    @foreach($prodiList as $prodi)
                        <option value="{{ $prodi['id'] }}">{{ $prodi['name'] }}</option>
                    @endforeach
                </flux:select>
            </flux:field>
            

            <flux:input wire:model="telepon" :label="__('No Telepon')" type="number" autocomplete="telepon" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
