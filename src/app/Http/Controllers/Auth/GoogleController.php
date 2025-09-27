<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect user ke halaman login Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback dari Google setelah user login/authorize.
     */
    public function callback()
    {
        try {
            // ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();
            $email = $googleUser->getEmail();

            // Cek apakah email sudah terdaftar
            $user = User::where('email', $email)->first();

            // Jika user belum ada, buat user baru
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'Google User',
                    'email' => $email,
                    'password' => bcrypt(Str::random(32)),
                    'email_verified_at' => now(),
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Update provider information if user exists but doesn't have provider info
                if (!$user->provider) {
                    $user->update([
                        'provider' => 'google',
                        'provider_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
            }

            // login user
            Auth::login($user, true);

            // arahkan ke dashboard
            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            // fallback kalau ada error
            return redirect()->route('login')
                ->withErrors(['oauth' => 'Login Google gagal: ' . $e->getMessage()]);
        }
    }
}