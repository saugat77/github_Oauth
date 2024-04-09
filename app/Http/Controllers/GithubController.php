<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver('github')->redirect();
    }

    public function callBack($provider)
    {
        $githubUser = Socialite::driver('github')->user();
        $user = User::updateOrCreate([
            'github_id' => $githubUser->id,
        ], [
            'name' => $githubUser->name ?? 'saugat',
            'email' => $githubUser->email ?? 'saugat@gmail.com',
            'github_token' => $githubUser->token,
            'provider' => $provider,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
