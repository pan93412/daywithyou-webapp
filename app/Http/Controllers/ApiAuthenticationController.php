<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class ApiAuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $input = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $userAgent = $request->header('User-Agent');

        if (auth()->attempt($input)) {
            $user = auth()->user();
            $token = $user->createToken($userAgent)->accessToken;
            return response()->json(['token' => $token], status: 201);
        }

        throw new AuthenticationException("Bad credentials.");
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function register(Request $request)
    {
        $input = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create($input);
        $token = $user->createToken($request->header('User-Agent'))->accessToken;

        return response()->json(['token' => $token], status: 201);
    }
}
