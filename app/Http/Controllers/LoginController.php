<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use GeneaLabs\LaravelSocialiter\Facades\Socialiter;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        return Socialite::driver("sign-in-with-apple")
        ->scopes(["name", "email"])
        ->redirect();
    }

    public function callback(Request $request)
    {
        // get abstract user object, not persisted
        $user = Socialite::driver("sign-in-with-apple")->user();

        // or use Socialiter to automatically manage user resolution and persistence
        // $user = Socialiter::driver("sign-in-with-apple")
        // ->login();

        dd($user);
    }
}
