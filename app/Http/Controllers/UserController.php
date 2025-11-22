<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
}

