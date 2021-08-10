<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        return response()->redirectToRoute('home');
    }

    /**
     * @return RedirectResponse
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('discord')->stateless()->redirect();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(): \Illuminate\Http\RedirectResponse
    {
        $response = Socialite::driver('discord')->stateless()->user();
        $user = User::where('nickname', $response->getNickname())->first();
        if ($user == null) {
            $user = User::create([
                'uid' => $response->getId(),
                'nickname' => $response->getNickname(),
                'name' => $response->getName(),
                'email' => $response->getEmail(),
                'avatar' => $response->getAvatar(),
                'data' => serialize($response->user),
            ]);
        }

        Auth::login($user, true);
        return response()->redirectToRoute('home');
    }
}
