<x-layouts.site :hero-dark="true">
    @include('website.hero')
    @include('website.about')
    @include('website.reviews')
    @include('website.menu-preview', ['featuredItems' => $featuredItems])
    @include('website.gallery')
    @include('website.reservation')
    @include('website.hours')
</x-layouts.site>
