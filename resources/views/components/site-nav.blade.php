@props(['overDark' => false])

{{-- Sticky nav. When `overDark` (a page with a dark full-bleed hero) it starts
     with light text over the image, then frosts to a solid cream bar with dark
     text once scrolled. On cream-top pages it's dark text throughout. --}}
<header
    x-data="{
        open: false,
        scrolled: false,
        overDark: {{ $overDark ? 'true' : 'false' }},
        get onDark() { return this.overDark && !this.scrolled; },
    }"
    @scroll.window="scrolled = window.scrollY > 24"
    :class="scrolled ? 'bg-cream-50/90 shadow-soft backdrop-blur' : 'bg-transparent'"
    class="fixed inset-x-0 top-0 z-40 transition-colors duration-300">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <span x-cloak x-show="onDark"><x-brand-mark tone="cream" class="h-9 w-9" /></span>
            <span x-cloak x-show="!onDark"><x-brand-mark class="h-9 w-9" /></span>
            <span class="font-display text-xl font-semibold tracking-wide transition-colors sm:text-2xl"
                  :class="onDark ? 'text-cream-50' : 'text-wine-800'">{{ __('site.brand.name') }}</span>
        </a>

        <div class="hidden items-center gap-8 lg:flex">
            @foreach (['about' => route('home').'#about', 'menu' => route('menu.index'), 'gallery' => route('home').'#gallery', 'hours' => route('home').'#hours'] as $key => $href)
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

    <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-50 bg-wine-950/97 p-6 lg:hidden">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <x-brand-mark tone="cream" class="h-9 w-9" />
                <span class="font-display text-2xl font-semibold text-cream-50">{{ __('site.brand.name') }}</span>
            </div>
            <button @click="open = false" type="button" class="text-cream-50" aria-label="{{ __('site.nav.close') }}">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="mt-12 flex flex-col gap-7">
            <a @click="open = false" href="{{ route('home') }}#about" class="font-display text-2xl font-medium text-cream-50">{{ __('site.nav.about') }}</a>
            <a @click="open = false" href="{{ route('menu.index') }}" class="font-display text-2xl font-medium text-cream-50">{{ __('site.nav.menu') }}</a>
            <a @click="open = false" href="{{ route('home') }}#gallery" class="font-display text-2xl font-medium text-cream-50">{{ __('site.nav.gallery') }}</a>
            <a @click="open = false" href="{{ route('home') }}#hours" class="font-display text-2xl font-medium text-cream-50">{{ __('site.nav.hours') }}</a>
            <a @click="open = false" href="{{ route('home') }}#reserve" class="btn-gold mt-2 w-full">{{ __('site.nav.reserve') }}</a>
            <div class="pt-2">
                <x-locale-switcher />
            </div>
        </div>
    </div>
</header>
