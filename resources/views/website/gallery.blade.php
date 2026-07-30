@php
    $galleryImages = [
        'gallery/khachapuri.jpg',
        'gallery/khinkali.jpg',
        'gallery/supra.jpg',
        'gallery/qvevri.jpg',
        'gallery/mtsvadi.jpg',
        'gallery/khachapuri-khinkali.jpg',
    ];
@endphp

<section id="gallery" class="bg-cream-50 py-24 lg:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="section-eyebrow reveal justify-center"><span class="rule-gold"></span>{{ __('site.gallery.eyebrow') }}<span class="rule-gold rotate-180"></span></p>
            <h2 class="section-title reveal mt-4">{{ __('site.gallery.title') }}</h2>
            <p class="reveal mt-4 text-lg text-ink-700">{{ __('site.gallery.lead') }}</p>
        </div>

        {{-- Editorial mosaic: first tile spans larger for rhythm. --}}
        <div class="mt-16 grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
            @foreach ($galleryImages as $index => $image)
                <div @class([
                    'reveal group relative overflow-hidden rounded-2xl',
                    'col-span-2 row-span-2 aspect-square sm:aspect-auto' => $index === 0,
                    'aspect-square' => $index !== 0,
                ]) style="--reveal-delay: {{ $index * 70 }}ms">
                    <img src="{{ asset('images/'.$image) }}" alt=""
                         class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-wine-950/30 to-transparent opacity-0 transition group-hover:opacity-100"></div>
                </div>
            @endforeach
        </div>
    </div>
</section>
