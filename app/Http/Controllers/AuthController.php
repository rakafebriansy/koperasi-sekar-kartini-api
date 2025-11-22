<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="loginUser",
     *     tags={"Authentication"},
     *     summary="Login a user with member number and password",
     *     description="Authenticate a user and return an API token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"member_number","password"},
     *             @OA\Property(property="member_number", type="string", example="123456"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="token", type="string", example="1|abcd1234efgh5678ijkl9012mnop3456qrst7890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid member number or password",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid member number or password.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Account is not verified or not active",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Account is not verified.")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'member_number' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('member_number', $credentials['member_number'])->first();

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

