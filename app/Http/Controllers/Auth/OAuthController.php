<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function redirect(): RedirectResponse
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();
        $providerId = (string) $githubUser->getId();
        $email = $githubUser->getEmail();

        $user = User::query()->updateOrCreate(
            ['provider' => 'github', 'provider_id' => $providerId],
            [
                'name' => $githubUser->getName() ?: $githubUser->getNickname() ?: 'GitHub User '.$providerId,
                'email' => $email ?: 'github-'.$providerId.'@users.noreply.local',
                'avatar' => $githubUser->getAvatar(),
                'email_verified_at' => $email ? now() : null,
            ],
        );

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
