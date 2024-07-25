<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserDetailResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::whereEmail($request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials are incorrect'
            ]);
        }

        $user->tokens()->delete();

        return response()->json([
            'data' => [
                'message' => 'Login Success!',
                'user' => $user->makeHidden(['created_at', 'updated_at', 'email_verified_at']),
                'token' => $user->createToken($user->name)->plainTextToken
            ]
        ]);
    }

    public function me()
    {
        $user = Auth::user();
        $user->load('role');
        return new UserDetailResource($user);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json(['message' => 'You are logged out.']);
    }
}
