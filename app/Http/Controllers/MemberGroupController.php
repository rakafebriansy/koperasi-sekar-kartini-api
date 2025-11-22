<?php

namespace App\Http\Controllers;

use App\Models\MemberGroup;
use App\Models\User;
use Illuminate\Http\Request;

class MemberGroupController extends Controller
{
    public function index()
    {
        $memberGroups = MemberGroup::with('workArea')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $memberGroups,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => ['required', 'string', 'unique:member_groups,number'],
            'description' => ['nullable', 'string'],
            'work_area_id' => ['nullable', 'exists:work_areas,id'],
        ]);

        $memberGroup = MemberGroup::create([
            'number' => $validated['number'],
            'description' => $validated['description'] ?? null,
            'work_area_id' => $validated['work_area_id'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Member group created successfully.',
            'data' => $memberGroup->load('workArea'),
        ], 201);
    }

    public function show(string $id)
    {
        $memberGroup = MemberGroup::with('workArea')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $memberGroup,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $memberGroup = MemberGroup::findOrFail($id);

        $validated = $request->validate([
            'number' => ['sometimes', 'required', 'string', 'unique:member_groups,number,' . $memberGroup->id],
            'description' => ['nullable', 'string'],
            'work_area_id' => ['nullable', 'exists:work_areas,id'],
        ]);

        $updateData = [];

        if (isset($validated['number'])) {
            $updateData['number'] = $validated['number'];
        }
        if (isset($validated['description'])) {
            $updateData['description'] = $validated['description'];
        }
        if (isset($validated['work_area_id'])) {
            $updateData['work_area_id'] = $validated['work_area_id'];
        }

        $memberGroup->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Member group updated successfully.',
            'data' => $memberGroup->fresh('workArea'),
        ]);
    }

    public function destroy(string $id)
    {
        $memberGroup = MemberGroup::findOrFail($id);

        $memberGroup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member group deleted successfully.',
        ]);
    }

    public function updateChairman(Request $request, string $id)
    {
        $memberGroup = MemberGroup::findOrFail($id);

        $validated = $request->validate([
            'chairman_id' => ['required', 'exists:users,id'],
        ]);

        $chairman = User::findOrFail($validated['chairman_id']);
        if ($chairman->role !== 'group_member') {
            return response()->json([
                'success' => false,
                'message' => 'Chairman must be a group member.',
            ], 403);
        }

        $memberGroup->update([
            'chairman_id' => $validated['chairman_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Chairman updated successfully.',
            'data' => $memberGroup->fresh('workArea'),
        ]);
    }

    public function updateFacilitator(Request $request, string $id)
    {
        $memberGroup = MemberGroup::findOrFail($id);

        $validated = $request->validate([
            'facilitator_id' => ['required', 'exists:users,id'],
        ]);

        $facilitator = User::findOrFail($validated['facilitator_id']);
        if ($facilitator->role !== 'group_member') {
            return response()->json([
                'success' => false,
                'message' => 'Facilitator must be a group member.',
            ], 403);
        }

        $memberGroup->update([
            'facilitator_id' => $validated['facilitator_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Facilitator updated successfully.',
            'data' => $memberGroup->fresh('workArea'),
        ]);
    }

    public function updateTreasurer(Request $request, string $id)
    {
        $memberGroup = MemberGroup::findOrFail($id);

        $validated = $request->validate([
            'treasurer_id' => ['required', 'exists:users,id'],
        ]);

        $treasurer = User::findOrFail($validated['treasurer_id']);
        if ($treasurer->role !== 'group_member') {
            return response()->json([
                'success' => false,
                'message' => 'Treasurer must be a group member.',
            ], 403);
        }

        $memberGroup->update([
            'treasurer_id' => $validated['treasurer_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Treasurer updated successfully.',
            'data' => $memberGroup->fresh('workArea'),
        ]);
    }
}

