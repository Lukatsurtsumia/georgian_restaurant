<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Special;

class HomeController extends Controller
{
    public function index()
    {
        $featuredItems = MenuItem::with('category')
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->get();

        // Categories with at least one item power the menu carousel.
        $menuCategories = MenuCategory::has('items')
            ->orderBy('sort_order')
            ->get();

        // Active specials (owner-managed) — shown only when there are any.
        $specials = Special::activeNow()->get();

        return view('page.home', compact('featuredItems', 'menuCategories', 'specials'));
    }
}
