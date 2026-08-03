@props([
    'item',
    'class' => 'aspect-[4/3]',
])

@php
    // Prefer a real uploaded image; otherwise map well-known dishes to the
    // gallery photography we ship; otherwise fall back to a monogram plate.
    $keywords = [
        // Real Pain et Pinot photography where we have it, stock otherwise.
        'khachapuri' => 'photos/dish-truffle-bread.jpg',
        'khinkali' => 'gallery/khinkali.jpg',
        'mtsvadi' => 'gallery/mtsvadi.jpg',
        'grill' => 'gallery/mtsvadi.jpg',
        'skewer' => 'gallery/mtsvadi.jpg',
        'pkhali' => 'photos/dish-mushrooms.jpg',
        'badrijani' => 'photos/dish-mushrooms.jpg',
        'lobio' => 'photos/dish-mushrooms.jpg',
        'cold' => 'photos/dish-mushrooms.jpg',
        'starter' => 'photos/dish-mushrooms.jpg',
        'churchkhela' => 'photos/dish-dessert.jpg',
        'gozinaki' => 'photos/dish-dessert.jpg',
        'dessert' => 'photos/dish-dessert.jpg',
        'saperavi' => 'photos/terrace.jpg',
        'rkatsiteli' => 'photos/terrace.jpg',
        'borjomi' => 'photos/terrace.jpg',
        'wine' => 'photos/terrace.jpg',
        'drink' => 'photos/terrace.jpg',
    ];

    $src = null;
    if (! empty($item->image)) {
        $src = \Illuminate\Support\Str::startsWith($item->image, ['http', '/'])
            ? $item->image
            : asset('storage/'.$item->image);
    } else {
        $haystack = \Illuminate\Support\Str::lower(($item->name_en ?? '').' '.($item->category?->name_en ?? ''));
        foreach ($keywords as $needle => $file) {
            if (\Illuminate\Support\Str::contains($haystack, $needle)) {
                $src = asset('images/'.$file);
                break;
            }
        }
    }

    $monogram = \Illuminate\Support\Str::of($item->name())->trim()->substr(0, 1)->upper();
@endphp

@if ($src)
    <div {{ $attributes->merge(['class' => $class.' overflow-hidden']) }}>
        <img src="{{ $src }}" alt="{{ $item->name() }}"
             class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
    </div>
@else
    {{-- Monogram plate: intentional placeholder, not a broken image. --}}
    <div {{ $attributes->merge(['class' => $class.' relative flex items-center justify-center overflow-hidden bg-gradient-to-br from-wine-800 to-wine-950']) }}>
        <div class="absolute inset-0 opacity-20 bg-paper-radial"></div>
        <span class="font-display text-5xl font-semibold text-gold-300/90">{{ $monogram }}</span>
        <span class="absolute bottom-3 text-[10px] font-medium uppercase tracking-[0.2em] text-cream-50/40">{{ __('site.menu.photo_soon') }}</span>
    </div>
@endif
