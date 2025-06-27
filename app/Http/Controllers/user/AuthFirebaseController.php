<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthFirebaseController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.user.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string'
    ]);

    $apiKey = config('services.firebase.api_key');

    $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key={$apiKey}", [
        'email' => $credentials['email'],
        'password' => $credentials['password'],
        'returnSecureToken' => true
    ]);

    if ($response->successful()) {
        $data = $response->json();

        $user = User::firstOrCreate(
            ['email' => $data['email']],
            ['name' => explode('@', $data['email'])[0], 'password' => bcrypt(str()->random(10))]
        );

        Auth::login($user);

        return redirect()->route('user.landingpage');
    } else {
        return back()->withErrors(['message' => 'Email atau password salah']);
    }
}

public function logout(Request $request)
{

    Session::flush();

    return response()->json(['message' => 'Logged out']);
}

}
