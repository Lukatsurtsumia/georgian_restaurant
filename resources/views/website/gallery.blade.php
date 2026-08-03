@php
    $galleryImages = [
        'photos/interior.jpg',
        'photos/dish-truffle-bread.jpg',
        'photos/wine-table.jpg',
        'photos/starter-pkhali.jpg',
        'photos/dish-mushrooms2.jpg',
        'photos/dish-dessert.jpg',
        'photos/terrace.jpg',
        'photos/dish-creamy.jpg',
    ];
@endphp

<section id="gallery" class="bg-cream-50 py-24 lg:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="section-eyebrow reveal justify-center"><span class="rule-gold"></span>{{ __('site.gallery.eyebrow') }}<span class="rule-gold rotate-180"></span></p>
            <h2 class="section-title reveal mt-4">{{ __('site.gallery.title') }}</h2>
            <p class="reveal mt-4 text-lg text-ink-700">{{ __('site.gallery.lead') }}</p>
        </div>

        <div class="mt-16 columns-2 gap-4 md:columns-3 md:gap-5 lg:columns-4">
            @foreach ($galleryImages as $index => $image)
                <div class="reveal group mb-4 break-inside-avoid overflow-hidden rounded-xl md:mb-5" style="--reveal-delay: {{ ($index % 4) * 80 }}ms">
                    <img src="{{ asset('images/'.$image) }}" alt=""
                         class="w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                </div>
            @endforeach
        </div>
    </div>
</section>
