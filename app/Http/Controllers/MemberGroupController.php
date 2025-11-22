<?php

namespace App\Http\Controllers;

use App\Models\MemberGroup;
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
}

