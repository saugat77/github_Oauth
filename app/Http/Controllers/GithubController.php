<?php

namespace App\Http\Controllers;

use App\Models\GithubCredentials;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;

class GithubController extends Controller
{
    public function githubCredStore(Request $request){
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
            'client_secret' => 'required',
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = new GithubCredentials;
        $data->client_id = $request->client_id;
        $data->client_secret = $request->client_secret;
        $data->save();
       return redirect()->to('login');
    }
    public function redirect()
    {
        $githubCredentials = GithubCredentials::first();
        if($githubCredentials === null){
            return view('github.index',compact('githubCredentials'));
        }

        $config = [
            'client_id' => $githubCredentials->client_id,
            'client_secret' => $githubCredentials->client_secret,
            'redirect' => '/auth/github/callback',
        ];

        config(['services.github' => $config]);

        // Redirect the user to GitHub for authentication
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
