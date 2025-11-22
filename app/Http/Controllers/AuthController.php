<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'member_number' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('member_number', $credentials['member_number'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid member number or password.'], 401);
        }

        if (! $user->is_verified) {
            return response()->json(['success' => false, 'message' => 'Account is not verified.'], 403);
        }

        if (! $user->is_active) {
            return response()->json(['success' => false, 'message' => 'Account is not active.'], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['success' => true, 'token' => $token]);
    }

    public function registerGroupMember(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'member_number' => ['required', 'string', 'unique:users,member_number'],
            'identity_number' => ['required', 'string', 'unique:users,identity_number'],
            'birth_date' => ['required', 'date'],
            'phone_number' => ['required', 'string'],
            'address' => ['required', 'string'],
            'occupation' => ['required', 'string'],
            'identity_card_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'self_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'password' => ['required', 'string', 'min:8'],
            'work_area_id' => ['nullable', 'exists:work_areas,id'],
        ]);

        $identityCardPhotoPath = null;
        $selfPhotoPath = null;

        if ($request->hasFile('identity_card_photo')) {
            $identityCardPhotoPath = $request->file('identity_card_photo')->store('employees/identity_cards', 'public');
        }

        if ($request->hasFile('self_photo')) {
            $selfPhotoPath = $request->file('self_photo')->store('employees/photos', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'member_number' => $validated['member_number'],
            'identity_number' => $validated['identity_number'],
            'birth_date' => $validated['birth_date'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'occupation' => $validated['occupation'],
            'identity_card_photo' => $identityCardPhotoPath,
            'self_photo' => $selfPhotoPath,
            'password' => Hash::make($validated['password']),
            'work_area_id' => $validated['work_area_id'] ?? null,
            'role' => 'group_member',
            'is_verified' => false,
            'is_active' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Please wait for verification.',
            'data' => $user,
        ], 201);
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

