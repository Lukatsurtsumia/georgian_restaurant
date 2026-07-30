<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;

class HomeController extends Controller
{
    public function index()
    {
        $featuredItems = MenuItem::with('category')
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->get();

        return view('page.home', compact('featuredItems'));
    }
}
