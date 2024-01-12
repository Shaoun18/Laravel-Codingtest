<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function redirect(){
        return Socialite::driver('github')->redirect();
    }

    public function callback(){
        try {

            $user = Socialite::driver('github')->user();

        }catch (\Exception $e){
          return redirect('/login');
        }

        $existuser= User::where('github_id',$user->id)->first();

        if ($existuser){
            Auth::login($existuser, true);
        }else {
            $newuser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'github_id' => $user->id,
                'password' => Hash::make(Str::random(10)),
            ]);

            Auth::login($newuser, true);
        }
        return redirect()->to('/dashboard');
    }
}

