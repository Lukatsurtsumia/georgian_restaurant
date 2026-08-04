<x-layouts.site :hero-dark="true">
    @include('website.hero')
    @include('website.specials', ['specials' => $specials])
    @include('website.about')
    @include('website.regions')
    @include('website.signatures', ['featuredItems' => $featuredItems])
    @include('website.menu-preview', ['featuredItems' => $featuredItems])
    @include('website.menu-carousel', ['menuCategories' => $menuCategories])
    @include('website.reviews')
    @include('website.reservation')
    @include('website.hours')
</x-layouts.site>
