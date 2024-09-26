<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
            return redirect(route('dashboard'));
        } else {
            abort(404);
        }
    }
    public function google()
    {
        return Socialite::driver('google')->redirect();
    }
    public function googleCallback()
    {
        $apiUrl = env('FASTAPI_URL', 'http://127.0.0.1:5000');
        $user = Socialite::driver('google')->stateless()->user();

        $localUser = User::where('email', $user->email)->first();

        if ($localUser) {
            Auth::login($localUser);
        } else {
            $newUser = User::create([
                'email' => $user->email,
                'name' => $user->name,
                'role_id' => 2,
                'count' => 10,
                'password' => bcrypt('admin123')
            ]);

            Auth::login($newUser);
        }

        if (Auth::user()->role_id == 1) {
            return redirect(route('admin-dashboard'));
        } else {
            return redirect(route('dashboard'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // Clear the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Редирект на Google для выхода
        return redirect(route('login'));
    }
}
