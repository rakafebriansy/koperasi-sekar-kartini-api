<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'member_number' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('member_number', $credentials['member_number'])->first();

        if (! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid member number or password.'], 401);
        }

        if (! $user->is_verified) {
            return response()->json(['success' => false, 'message' => 'Account is not verified.'], 403);
        }

        if (! $user->is_active) {
            return response()->json(['success' => false, 'message' => 'Account is not active.'], 403);
        }

        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['success' => true, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }
        
        return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
    }
}

