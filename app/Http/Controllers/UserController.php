<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="User management endpoints"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Put(
     *     path="/api/verified/{id}",
     *     tags={"Users"},
     *     summary="Update user verification status",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"is_verified"},
     *             @OA\Property(property="is_verified", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User verification status updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User verification status updated successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Cannot update admin user status")
     * )
     */
    public function updateVerified(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update admin user status.',
            ], 403);
        }

        $validated = $request->validate([
            'is_verified' => ['required', 'boolean'],
        ]);

        $user->update([
            'is_verified' => $validated['is_verified'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User verification status updated successfully.',
            'data' => $user->fresh(),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/active/{id}",
     *     tags={"Users"},
     *     summary="Update user active status",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"is_active"},
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User active status updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User active status updated successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Cannot update admin user status")
     * )
     */
    public function updateActive(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update admin user status.',
            ], 403);
        }

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $user->update([
            'is_active' => $validated['is_active'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User active status updated successfully.',
            'data' => $user->fresh(),
        ]);
    }

    /**
     * @OA\Patch(
     *     path="/api/users/{id}/group",
     *     tags={"Users"},
     *     summary="Update user group assignment",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"group_id"},
     *             @OA\Property(property="group_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User group updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User group updated successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Only group members can be assigned to a group")
     * )
     */
    public function updateGroupId(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'group_member') {
            return response()->json([
                'success' => false,
                'message' => 'Only group members can be assigned to a group.',
            ], 403);
        }

        $validated = $request->validate([
            'group_id' => ['required', 'exists:member_groups,id'],
        ]);

        $user->update([
            'group_id' => $validated['group_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User group updated successfully.',
            'data' => $user->fresh(),
        ]);
    }
}

