<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('board');
        }

        $ua = $request->header('User-Agent', '');
        $isMobileApp = str_contains($ua, 'KlikBan') || $request->cookie('is_klikban_app') === '1';
        if ($isMobileApp) {
            return redirect()->route('board');
        }

        return view('home');
    }

    public function about()
    {
        return view('about');
    }
}
