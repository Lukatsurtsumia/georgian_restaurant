@php
    $locales = ['en' => 'EN', 'fr' => 'FR'];
    $current = app()->getLocale();
@endphp

<div class="inline-flex items-center gap-1 rounded-full border border-white/15 bg-white/5 p-1 backdrop-blur"
     role="group" aria-label="{{ __('site.nav.lang_label') }}">
    @foreach ($locales as $code => $label)
        <a href="{{ route('locale.switch', $code) }}"
           @if ($code === $current) aria-current="true" @endif
           class="inline-flex min-w-[2.25rem] items-center justify-center rounded-full px-3 py-1.5 text-xs font-semibold tracking-wide transition
                  {{ $code === $current
                       ? 'bg-white text-stone-900 shadow-sm'
                       : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            {{ $label }}
        </a>
    @endforeach
</div>
