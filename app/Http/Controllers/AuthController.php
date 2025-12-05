<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Authentication endpoints"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="User login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone_number","password"},
     *             @OA\Property(property="phone_number", type="string", example="081200000001"),
     *             @OA\Property(property="password", type="string", example="admin123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="token", type="string", example="1|abcdefghijklmnopqrstuvwxyz"),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials"),
     *     @OA\Response(response=403, description="Account not verified or not active")
     * )
     */
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

        if (!$user->is_verified) {
            return response()->json(['success' => false, 'message' => 'Account is not verified.'], 403);
        }

        if (!$user->is_active) {
            return response()->json(['success' => false, 'message' => 'Account is not active.'], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['success' => true, 'token' => $token, 'data' => new UserResource($user)]);
    }

    /**
     * @OA\Post(
     *     path="/api/register-group-member",
     *     tags={"Authentication"},
     *     summary="Register new group member",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "member_number",
     *                     "identity_number",
     *                     "birth_date",
     *                     "phone_number",
     *                     "address",
     *                     "occupation",
     *                     "identity_card_photo",
     *                     "self_photo",
     *                     "password",
     *                     "work_area_id"
     *                 },
     *                 @OA\Property(property="name", type="string", example="Budi Santoso"),
     *                 @OA\Property(property="member_number", type="string", example="MBR-001", description="Nomor anggota unik"),
     *                 @OA\Property(property="identity_number", type="string", example="3201010101900001", description="NIK unik"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="1990-01-01"),
     *                 @OA\Property(property="phone_number", type="string", example="081234567890", description="Nomor telepon unik"),
     *                 @OA\Property(property="address", type="string", example="Jl. Raya No. 123"),
     *                 @OA\Property(property="occupation", type="string", example="Petani"),
     *                 @OA\Property(property="identity_card_photo", type="string", format="binary"),
     *                 @OA\Property(property="self_photo", type="string", format="binary"),
     *                 @OA\Property(property="password", type="string", example="password123"),
     *                 @OA\Property(property="work_area_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registration successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Registration successful. Please wait for verification."),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function registerGroupMember(Request $request)
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
            'is_verified' => false,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Please wait for verification.',
            'data' => $user,
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Authentication"},
     *     summary="User logout",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged out successfully.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
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

}

