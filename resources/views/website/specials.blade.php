@php $items = $specials ?? collect(); @endphp

@if ($items->isNotEmpty())
<section class="relative overflow-hidden bg-wine-800 py-12 text-cream-50 lg:py-14">
    <div class="absolute inset-0 bg-paper-radial opacity-60"></div>
    <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <p class="section-eyebrow reveal text-gold-300"><span class="rule-gold"></span>{{ __('site.specials.eyebrow') }}</p>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($items as $s)
                <div class="reveal flex items-start gap-4 rounded-2xl border border-cream-50/10 bg-cream-50/5 p-5 backdrop-blur" style="--reveal-delay: {{ $loop->index * 80 }}ms">
                    @if ($s->badge)
                        <span class="inline-flex shrink-0 items-center rounded-lg bg-gold-400 px-3 py-1.5 font-display text-sm font-bold text-wine-950">{{ $s->badge }}</span>
                    @else
                        <svg class="h-6 w-6 shrink-0 text-gold-300" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/></svg>
                    @endif
                    <div>
                        <h3 class="font-display text-xl font-semibold leading-tight">{{ $s->title() }}</h3>
                        @if ($s->description())
                            <p class="mt-1.5 text-sm leading-relaxed text-cream-50/80">{{ $s->description() }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
