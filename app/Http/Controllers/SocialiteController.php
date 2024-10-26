<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    function google_callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $now = new DateTime();
        $timestamp = $now->getTimestamp();

        $user = User::updateOrCreate([
            'google_id' => $googleUser->id,
        ], [
            'given_name' => $googleUser->user['given_name'],
            'family_name' => $googleUser->user['family_name'],
            'avatar' => $googleUser->user['picture'],
            'email' => $googleUser->email,
            'password' => Hash::make($timestamp),
            'google_token' => $googleUser->token,
            'google_refresh_token' => $googleUser->refreshToken,
        ]);

        Auth::login($user);

        return redirect('/dashboard');

    }
}
