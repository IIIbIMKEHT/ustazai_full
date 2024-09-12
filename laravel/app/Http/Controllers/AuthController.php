<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }
    public function simpleAuth(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required']
        ]);
        
        if (Auth::attempt($credentials)) {
            
            $request->session()->regenerate();
 
            return redirect(route('dashboard'));
        }
    }
    public function google()
    {
        return Socialite::driver('google')->redirect();
    }
    public function googleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();

        $localUser = User::where('email', $user->email)->first();

        if ($localUser) {
            Auth::login($localUser);
        } else {
            $newUser = User::create([
                'email' => $user->email,
                'name' => $user->name,
                'password' => bcrypt('admin123')
            ]);
            Auth::login($newUser);
        }

        return redirect(route('dashboard'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
}
