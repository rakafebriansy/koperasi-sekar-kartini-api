<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone_number' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('phone_number', $credentials['phone_number'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid phone number or password.'], 401);
        }

        if (!$user->is_active) {
            return response()->json(['success' => false, 'message' => 'Account is not active.'], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['success' => true, 'token' => $token, 'data' => new UserResource($user)]);
    }


    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // 'member_number' => ['required', 'string', 'unique:users,identity_number'],
            'identity_number' => ['required', 'string', 'unique:users,identity_number'],
            'birth_date' => ['required', 'date'],
            'phone_number' => ['required', 'string', 'unique:users,phone_number'],
            'address' => ['required', 'string'],
            'occupation' => ['required', 'string'],
            'identity_card_photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'self_photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'password' => ['required', 'string', 'min:8'],
            'work_area_id' => ['required', 'exists:work_areas,id'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'identity_number.required' => 'NIK wajib diisi.',
            'identity_number.unique' => 'NIK sudah terdaftar.',

            // 'member_number.required' => 'Nomor Anggota wajib diisi.',
            // 'member_number.unique' => 'Nomor Anggota sudah terdaftar.',

            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',

            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.unique' => 'Nomor telepon sudah terdaftar.',

            'address.required' => 'Alamat wajib diisi.',

            'occupation.required' => 'Pekerjaan wajib diisi.',

            'identity_card_photo.required' => 'Foto KTP wajib diunggah.',
            'identity_card_photo.image' => 'File foto KTP harus berupa gambar.',
            'identity_card_photo.mimes' => 'Foto KTP harus berformat JPG, JPEG, atau PNG.',
            'identity_card_photo.max' => 'Foto KTP maksimal berukuran 2MB.',

            'self_photo.required' => 'Pas Foto wajib diunggah.',
            'self_photo.image' => 'File Pas Foto harus berupa gambar.',
            'self_photo.mimes' => 'Pas Foto harus berformat JPG, JPEG, atau PNG.',
            'self_photo.max' => 'Pas Foto maksimal berukuran 2MB.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',

            'work_area_id.required' => 'Wilayah kerja wajib dipilih.',
            'work_area_id.exists' => 'Wilayah kerja tidak valid.',
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
            // 'member_number' => $validated['member_number'],
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
            return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Log out failed.']);

    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 401);
        }

        $request->user()->currentAccessToken()->delete();

        $newToken = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $newToken,
            'data' => new UserResource($user)
        ]);
    }

    public function storeFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string'
        ]);

        $user = $request->user();
        $user->update([
            'fcm_token' => $request->fcm_token
        ]);

        return response()->json(['success' => true, 'status' => 'ok']);
    }
}

