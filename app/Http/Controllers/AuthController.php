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
     *             @OA\Property(property="phone_number", type="string", example="087712345678"),
     *             @OA\Property(property="password", type="string", example="password")
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
            return response()->json(['success' => false, 'message' => 'Invalid member number or password.'], 401);
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
     *                 required={"name","member_number","identity_number","birth_date","phone_number","address","occupation","password"},
     *                 @OA\Property(property="name", type="string", example="Budi Santoso"),
     *                 @OA\Property(property="member_number", type="string", example="MEM001"),
     *                 @OA\Property(property="identity_number", type="string", example="3201010101900001"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="1990-01-01"),
     *                 @OA\Property(property="phone_number", type="string", example="081234567890"),
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
        }

        return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
    }
}

