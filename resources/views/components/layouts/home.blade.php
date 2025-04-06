<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
      <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:brand href="#" logo="https://fluxui.dev/img/demo/logo.png" name="Acme Inc." class="max-lg:hidden dark:hidden" />
            <flux:brand href="#" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc." class="max-lg:hidden! hidden dark:flex" />
            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="home" href="#" current>Home</flux:navbar.item>
                <flux:navbar.item icon="inbox" badge="12" href="#">Inbox</flux:navbar.item>
                <flux:navbar.item icon="document-text" href="#">Documents</flux:navbar.item>
                <flux:navbar.item icon="calendar" href="#">Calendar</flux:navbar.item>
                <flux:separator vertical variant="subtle" class="my-2"/>
                <flux:dropdown class="max-lg:hidden">
                    <flux:navbar.item icon:trailing="chevron-down">Favorites</flux:navbar.item>
                    <flux:navmenu>
                        <flux:navmenu.item href="#">Marketing site</flux:navmenu.item>
                        <flux:navmenu.item href="#">Android app</flux:navmenu.item>
                        <flux:navmenu.item href="#">Brand guidelines</flux:navmenu.item>
                    </flux:navmenu>
                </flux:dropdown>
            </flux:navbar>
            <flux:spacer />
            <flux:navbar class="me-4">
                <flux:navbar.item icon="magnifying-glass" href="#" label="Search" />
                <flux:navbar.item class="max-lg:hidden" icon="cog-6-tooth" href="#" label="Settings" />
                <flux:navbar.item class="max-lg:hidden" icon="information-circle" href="#" label="Help" />
            </flux:navbar>
            @auth
                  <flux:dropdown position="top" align="end">
                        <flux:profile
                              class="cursor-pointer"
                              :initials="auth()->user()->initials()"
                        />

                        <flux:menu>
                              <flux:menu.radio.group>
                              <div class="p-0 text-sm font-normal">
                                    <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                          <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                          <span
                                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                          >
                                                {{ auth()->user()->initials() }}
                                          </span>
                                          </span>

                                          <div class="grid flex-1 text-left text-sm leading-tight">
                                          <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                          <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                          </div>
                                    </div>
                              </div>
                              </flux:menu.radio.group>

                              <flux:menu.separator />

                              <flux:menu.radio.group>
                              <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                              </flux:menu.radio.group>

                              <flux:menu.separator />

                              <form method="POST" action="{{ route('logout') }}" class="w-full">
                              @csrf
                              <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                                    {{ __('Log Out') }}
                              </flux:menu.item>
                              </form>
                        </flux:menu>
                  </flux:dropdown>
                  @else
                  <flux:dropdown position="top" align="end">
                        <flux:button icon:trailing="chevron-down">Open</flux:button>
                        <flux:menu>
                              <flux:menu.radio.group>
                              <flux:menu.item :href="route('login')" icon="cog" wire:navigate>{{ __('Login') }}</flux:menu.item>
                              </flux:menu.radio.group>

                              <flux:menu.separator />

                              <flux:menu.radio.group>
                              <flux:menu.item :href="route('register')" icon="cog" wire:navigate>{{ __('Register') }}</flux:menu.item>
                              </flux:menu.radio.group>
                        </flux:menu>
                  </flux:dropdown>
                  @endauth
        </flux:header>
        <flux:sidebar stashable sticky class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
            <flux:brand href="#" logo="https://fluxui.dev/img/demo/logo.png" name="Acme Inc." class="px-2 dark:hidden" />
            <flux:brand href="#" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc." class="px-2 hidden dark:flex" />
            <flux:navlist variant="outline">
                <flux:navlist.item icon="home" href="#" current>Home</flux:navlist.item>
                <flux:navlist.item icon="inbox" badge="12" href="#">Inbox</flux:navlist.item>
                <flux:navlist.item icon="document-text" href="#">Documents</flux:navlist.item>
                <flux:navlist.item icon="calendar" href="#">Calendar</flux:navlist.item>
                <flux:navlist.group expandable heading="Favorites" class="max-lg:hidden">
                    <flux:navlist.item href="#">Marketing site</flux:navlist.item>
                    <flux:navlist.item href="#">Android app</flux:navlist.item>
                    <flux:navlist.item href="#">Brand guidelines</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
            <flux:spacer />
            <flux:navlist variant="outline">
                <flux:navlist.item icon="cog-6-tooth" href="#">Settings</flux:navlist.item>
                <flux:navlist.item icon="information-circle" href="#">Help</flux:navlist.item>
            </flux:navlist>
        </flux:sidebar>
        <flux:main container>
            <flux:heading size="xl" level="1">Good afternoon, Olivia</flux:heading>
            <flux:text class="mt-2 mb-6 text-base">Here's what's new today</flux:text>
            <flux:separator variant="subtle" />
        </flux:main>


        {{ $slot }}

        @fluxScripts
    </body>
</html>
