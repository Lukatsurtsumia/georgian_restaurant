@php
    $locales = ['en' => 'EN', 'fr' => 'FR'];
    $current = app()->getLocale();
@endphp

<div class="inline-flex items-center gap-1 rounded-full border border-stone-200 bg-stone-50 p-1"
     role="group" aria-label="{{ __('site.nav.lang_label') }}">
    @foreach ($locales as $code => $label)
        <a href="{{ route('locale.switch', $code) }}"
           @if ($code === $current) aria-current="true" @endif
           class="inline-flex min-w-[2.25rem] items-center justify-center rounded-full px-3 py-1 text-xs font-semibold tracking-wide transition
                  {{ $code === $current
                       ? 'bg-wine-700 text-white shadow-sm'
                       : 'text-stone-500 hover:bg-white hover:text-stone-800' }}">
            {{ $label }}
        </a>
    @endforeach
</div>
