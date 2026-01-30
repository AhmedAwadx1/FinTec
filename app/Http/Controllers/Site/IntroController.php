<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class IntroController extends Controller
{
    /**
     * Display the intro/home page
     */
    public function index()
    {
        return view('site.intro');
    }

    /**
     * Display the privacy policy page
     */
    public function privacyPolicy()
    {
        return view('site.privacy-policy');
    }

    /**
     * Display the delete account page
     */
    public function deleteAccount()
    {
        return view('site.delete-account');
    }

    /**
     * Set the application language
     */
    public function SetLanguage($lang)
    {
        if (in_array($lang, ['en', 'ar'])) {
            App::setLocale($lang);
            Session::put('locale', $lang);
        }
        
        return redirect()->back();
    }
}
