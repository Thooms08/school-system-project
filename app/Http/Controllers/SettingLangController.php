<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;

class SettingLangController extends Controller
{
    /**
     * Show the language settings page.
     */
    public function index()
    {
        $currentLocale = session('locale', config('app.locale', 'en'));
        return view('dashboard_admin.setting-lang', compact('currentLocale'));
    }

    /**
     * Switch the application language and store it in the session.
     */
    public function switch(Request $request): RedirectResponse
    {
        $locale = $request->input('locale', 'en');

        $supportedLocales = ['en', 'id'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'en';
        }

        session(['locale' => $locale]);
        App::setLocale($locale);

        return redirect()->back()->with('success', __('general.language_changed'));
    }
}
