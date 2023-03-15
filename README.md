
## Step 1: Register an App ID
- Input Description = ""
- Input Identifier = ""
- Sign In With Apple -> Edit -> Enable as a primary App ID -> Save -> continue -> register

## Step 2: Register a Services ID
- Input Description = "NTA App Sign In Apple"
- Input Identifier = "com.nitrotechasia.server.staging"
- Check Enabled Sign In With Apple -> Configure
    ```code
	// Primary App ID
	select App ID created above

	// Web Domain 
	login-apple.herokuapp.com

	// Return URLs
	https://login-apple.herokuapp.com/api/v1/login-callback
    ```
   -> save -> continue -> register

## Step 3: Register a New Key
- Input Key Name
- Check Sign In With Apple -> Configure
    ```code
	// Choose a Primary App ID
	select App ID created above
    ```
   -> save -> continue -> register

   -> Download file key .p8 -> `Rename file to key.txt`

## Step 4: Create Client Secret
- Install ruby (linux)

- Install JWT GEM
```bash
sudo gem install jwt
```

- Create file `client_sectet.rb` with content:

```json
require 'jwt'

key_file = 'key.txt'    // file .p8 rename
team_id = ''
client_id = '' 
key_id = ''     // key file .p8

ecdsa_key = OpenSSL::PKey::EC.new IO.read key_file

headers = {
'kid' => key_id
}

claims = {
    'iss' => team_id,
    'iat' => Time.now.to_i,
    'exp' => Time.now.to_i + 86400*180,
    'aud' => 'https://appleid.apple.com',
    'sub' => client_id,
}

token = JWT.encode claims, ecdsa_key, 'ES256', headers

puts token
```

> Note: 
>
> `team_id`: This can be found on the top-right corner when logged into your Apple Developer account, right under your name.
>
> `client_id`: This is the identifier from the Service Id created in step 2 above, for example com.example.service
>
> `key_id`: This is the identifier of the private key created in step 3 above.

- Save the file and run it from the terminal. It will spit out a JWT which is your client secret, which you will need to add to your .env file in the next step.
```code
   ruby client_secret.rb   -> copy result command - client_secret
```


## Laravel
- Config .env
```
APP_ENV=production

SIGN_IN_WITH_APPLE_LOGIN="/api/v1/login-apple"
SIGN_IN_WITH_APPLE_REDIRECT="/api/v1/login-callback"
SIGN_IN_WITH_APPLE_CLIENT_ID="com.nitrotechasia.server.staging"
SIGN_IN_WITH_APPLE_CLIENT_SECRET="" // your app's client secret as calculated in step 4
```

- Install the composer package:
```bash
composer require genealabs/laravel-sign-in-with-apple
```

- routes/web.php
```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/v1/login-apple', [LoginController::class, 'login'])->name('login');

Route::post('/api/v1/login-callback', [LoginController::class, 'callback'])->name('callback');
```


- app/Http/Middleware/VerifyCsrfToken.php
```php
    protected $except = [
        "/api/v1/login-callback",
    ];
```

- app/Http/Controllers/LoginController.php
```php
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

```

- resources/views/welcome.blade.php
```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
    </head>
    <body class="antialiased">
        <form action="{{ route('login') }}">
            @csrf
            @signInWithApple("black", true, "sign-in", 6)
        </form>
        
    </body>
</html>

```