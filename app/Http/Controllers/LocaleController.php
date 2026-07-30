<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        validator(['locale' => $locale], [
            'locale' => ['required', Rule::in(SetLocale::SUPPORTED)],
        ])->validate();

        $request->session()->put('locale', $locale);

        return back();
    }
}
