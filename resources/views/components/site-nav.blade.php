@props(['overDark' => false])

{{-- Sticky nav. When `overDark` (a page with a dark full-bleed hero) it starts
     with light text over the image, then frosts to a solid cream bar with dark
     text once scrolled. On cream-top pages it's dark text throughout.
     The mobile overlay is a SIBLING of the header (not a child) — a header with
     backdrop-filter traps fixed children, which used to shrink the menu. --}}
<div
    x-data="{
        open: false,
        scrolled: false,
        overDark: {{ $overDark ? 'true' : 'false' }},
        get onDark() { return this.overDark && !this.scrolled; },
    }"
    @scroll.window="scrolled = window.scrollY > 24">

    <header
        :class="scrolled ? 'bg-cream-50/90 shadow-soft backdrop-blur' : (onDark ? 'bg-gradient-to-b from-wine-950/80 via-wine-950/40 to-transparent' : 'bg-transparent')"
        class="fixed inset-x-0 top-0 z-40 transition-colors duration-300">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <span x-cloak x-show="onDark"><x-brand-mark tone="cream" class="h-9 w-9" /></span>
                <span x-cloak x-show="!onDark"><x-brand-mark class="h-9 w-9" /></span>
                <span class="font-display text-xl font-semibold tracking-wide transition-colors sm:text-2xl"
                      :class="onDark ? 'text-cream-50' : 'text-wine-800'">{{ __('site.brand.name') }}</span>
            </a>

            <div class="hidden items-center gap-8 lg:flex">
                @foreach (['about' => route('home').'#about', 'menu' => route('menu.index'), 'hours' => route('home').'#hours'] as $key => $href)
                    <a href="{{ $href }}" class="text-sm font-medium transition-colors"
                       :class="onDark ? 'text-cream-50/90 hover:text-cream-50' : 'text-ink-700 hover:text-wine-700'">{{ __('site.nav.'.$key) }}</a>
                @endforeach
            </div>

            <div class="hidden items-center gap-4 lg:flex">
                <x-locale-switcher-light />
                <a href="{{ route('home') }}#reserve" class="btn-primary !px-6 !py-2.5">{{ __('site.nav.reserve') }}</a>
            </div>

            <button @click="open = true" type="button" class="rounded-full p-1.5 transition-colors lg:hidden"
                    :class="onDark ? 'text-cream-50' : 'text-wine-800'" aria-label="{{ __('site.nav.reserve') }}">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
            </button>
        </nav>
    </header>

    {{-- Mobile menu: full-screen, opaque dark-red panel --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex flex-col bg-gradient-to-br from-wine-800 via-wine-900 to-wine-950 lg:hidden">
        <div class="absolute inset-0 bg-paper-radial opacity-40"></div>

        {{-- top bar --}}
        <div class="relative flex items-center justify-between border-b border-cream-50/10 px-6 py-4">
            <div class="flex items-center gap-2.5">
                <x-brand-mark tone="cream" class="h-9 w-9" />
                <span class="font-display text-xl font-semibold text-cream-50">{{ __('site.brand.name') }}</span>
            </div>
            <button @click="open = false" type="button" class="rounded-full p-1.5 text-cream-50 transition hover:bg-cream-50/10" aria-label="{{ __('site.nav.close') }}">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- centered links --}}
        <nav class="relative flex flex-1 flex-col justify-center gap-1 px-8">
            @foreach (['about' => route('home').'#about', 'menu' => route('menu.index'), 'hours' => route('home').'#hours'] as $key => $href)
                <a @click="open = false" href="{{ $href }}"
                   class="border-b border-cream-50/10 py-4 font-display text-3xl font-medium text-cream-50 transition hover:text-gold-300">{{ __('site.nav.'.$key) }}</a>
            @endforeach
        </nav>

        {{-- bottom actions --}}
        <div class="relative space-y-5 px-8 pb-10">
            <a @click="open = false" href="{{ route('home') }}#reserve" class="btn-gold w-full">{{ __('site.nav.reserve') }}</a>
            <div class="flex items-center justify-between">
                <x-locale-switcher />
                <a href="tel:{{ config('restaurant.contact.phone_href') }}" class="text-sm font-semibold text-gold-300">{{ config('restaurant.contact.phone') }}</a>
            </div>
        </div>
    </div>
</div>
