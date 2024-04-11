<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function redirect()
    {
        //This is where the url hits and after this call back function is called if the github client and secret key is valid
        return Socialite::driver('github')->redirect();
    }

    public function callBack()
    {
        $githubUser = Socialite::driver('github')->user();
        $user = User::updateOrCreate([
            'github_id' => $githubUser->id,
        ], [
             // My github email and name was not public so i randomly given out my name and email if the github email is not public
            'name' => $githubUser->name ?? 'zdasasd',
            'email' => $githubUser->email ?? 'xuzsda@gmail.com',
            'github_token' => $githubUser->token,
            'provider' => 'github',
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
