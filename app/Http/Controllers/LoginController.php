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

    public function token()
    {
        $token = "eyJraWQiOiJmaDZCczhDIiwiYWxnIjoiUlMyNTYifQ.eyJpc3MiOiJodHRwczovL2FwcGxlaWQuYXBwbGUuY29tIiwiYXVkIjoiY29tLm5pdHJvdGVjaGFzaWEuc2VydmVyLnN0YWdpbmciLCJleHAiOjE2NzkzODU2NTAsImlhdCI6MTY3OTI5OTI1MCwic3ViIjoiMDAwMjY2LjQxY2NkNzVhZGY3MjQxZjg5MGE5N2MyNDNhMTM4MzJmLjA4NTgiLCJhdF9oYXNoIjoiSGk4SmlSc2J1ak1QUjgwdm9QUlhaQSIsImVtYWlsIjoiaG9hbnYubnRhQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjoidHJ1ZSIsImF1dGhfdGltZSI6MTY3OTI5OTI0OSwibm9uY2Vfc3VwcG9ydGVkIjp0cnVlfQ.SMYacOn1-8XAQ44hjWnGOBL3s42BXue8GtA6EMgYupMrgY5wH86ZNFOYY0bSY9BFTROx4c1BJIKoFV55wGwj7101Htdogku13WBKQlteJSYlt9zIqXS8xBrUFZfWm0kDI5a2W_BAWSq4Ipx3l6-SlkxeZFlhtweUou-x_NvQOAbhEJOQFVIanUr7HBn33uMpYGG7oxZiEvTxqChDDqkuEgEmnYWuGKcCa91_TqZF4TeH6UeiJvIIfTxB1-XFJnvT0ZPvcLHfUHgnhMSYL8y0C2f3BxK2j-j8BgxGNZZq1wBoyGqH1RnQlgh1Rtz5TdY3ysuKqPrybwEVrhXAAL4uRg";
        $user = Socialite::driver("sign-in-with-apple")->userFromToken($token);
        dd($user);
    }
}
