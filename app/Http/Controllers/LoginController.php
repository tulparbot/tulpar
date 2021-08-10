<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
        $url = 'https://discord.com/api/oauth2/authorize?client_id={client_id}&permissions={permissions}&redirect_uri={redirect_uri}&response_type=code&scope={scope}';
        $client_id = config('discord.client.id');
        $permissions = 8;
        $redirect_uri = config('discord.redirect.uri');
        $scopes = [
            'guilds',
            // 'guilds.join',
            // 'bot',
            'email',
        ];

        $url = Str::of($url)
            ->replace('{client_id}', urlencode($client_id))
            ->replace('{permissions}', urlencode($permissions))
            ->replace('{redirect_uri}', urlencode($redirect_uri))
            ->replace('{scope}', implode('%20', $scopes));

        return redirect($url);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(): \Illuminate\Http\RedirectResponse
    {
        try {
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
        } catch (Exception $exception) {
            Log::error($exception->getTraceAsString());
            abort(403);
        }
        return redirect()->home();
    }
}
