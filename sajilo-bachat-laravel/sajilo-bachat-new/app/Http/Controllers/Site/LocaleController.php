<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale)
    {
        $request->session()->put('locale', $locale);
        Cookie::queue('locale', $locale, 60 * 24 * 365);

        // Only bounce back to a same-site URL.
        $previous = url()->previous();
        $back = str_starts_with($previous, url('/')) ? $previous : route('home');

        return redirect()->to($back);
    }
}
