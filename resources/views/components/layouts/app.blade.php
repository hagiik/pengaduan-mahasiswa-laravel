<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        <div class="flex-1 self-stretch max-md:pt-6">
            <flux:heading>{{ $heading ?? '' }}</flux:heading>
            <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>
                {{ $slot }}
        </div>
    </flux:main>
</x-layouts.app.sidebar>
