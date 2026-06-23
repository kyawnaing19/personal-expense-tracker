<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepositories;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct(
        private UserRepositories $userRepository
    ) {}

    // Google ကို redirect လုပ်တယ်
    // public function redirectToGoogle()
    // {
    //     return Socialite::driver('google')
    //         ->stateless()
    //         ->redirect();
    // }

    // Google ကနေ callback ပြန်လာတယ်
    // public function handleGoogleCallback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')
    //             ->stateless()
    //             ->user();

    //         $user = $this->userRepository->findOrCreateByGoogle([
    //             'google_id' => $googleUser->getId(),
    //             'name' => $googleUser->getName(),
    //             'email' => $googleUser->getEmail(),
    //             'avatar' => $googleUser->getAvatar(),
    //         ]);

    //         auth()->login($user, true);
    //         session()->regenerate();

    //         return redirect()->intended(route('dashboard'));

    //     } catch (\Exception $e) {
    //         dd($e->getMessage());
    //     }
    // }

    // public function logout()
    // {
    //     auth()->logout();

    //     return redirect()->route('login');
    // }
}
