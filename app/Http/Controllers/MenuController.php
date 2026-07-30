<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;

class MenuController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::with('items')->orderBy('sort_order')->get();

        return view('page.menu', compact('categories'));
    }
}
