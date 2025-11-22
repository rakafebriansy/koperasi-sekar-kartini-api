<?php

namespace App\Http\Controllers;

use App\Models\MemberGroup;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Member Groups",
 *     description="Member group management endpoints"
 * )
 */
class MemberGroupController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/groups",
     *     tags={"Member Groups"},
     *     summary="Get all member groups",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/MemberGroup"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        $memberGroups = MemberGroup::with('workArea')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $memberGroups,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/groups",
     *     tags={"Member Groups"},
     *     summary="Create new member group",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"number"},
     *             @OA\Property(property="number", type="string", example="GRP001"),
     *             @OA\Property(property="description", type="string", example="Kelompok tani sejahtera"),
     *             @OA\Property(property="work_area_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Member group created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Member group created successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/MemberGroup")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/groups/{id}",
     *     tags={"Member Groups"},
     *     summary="Get member group by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/MemberGroup")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $memberGroup = MemberGroup::with('workArea')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $memberGroup,
        ]);
    }

    /**
     * @OA\Patch(
     *     path="/api/groups/{id}",
     *     tags={"Member Groups"},
     *     summary="Update member group",
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
     *             @OA\Property(property="number", type="string", example="GRP001"),
     *             @OA\Property(property="description", type="string", example="Kelompok tani sejahtera"),
     *             @OA\Property(property="work_area_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Member group updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Member group updated successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/MemberGroup")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/groups/{id}",
     *     tags={"Member Groups"},
     *     summary="Delete member group",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Member group deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Member group deleted successfully.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $memberGroup = MemberGroup::findOrFail($id);

        $memberGroup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member group deleted successfully.',
        ]);
    }

    /**
     * @OA\Patch(
     *     path="/api/groups/chairman/{id}",
     *     tags={"Member Groups"},
     *     summary="Update chairman of member group",
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
     *             required={"chairman_id"},
     *             @OA\Property(property="chairman_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chairman updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Chairman updated successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/MemberGroup")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Chairman must be a group member")
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/api/groups/facilitator/{id}",
     *     tags={"Member Groups"},
     *     summary="Update facilitator of member group",
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
     *             required={"facilitator_id"},
     *             @OA\Property(property="facilitator_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Facilitator updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Facilitator updated successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/MemberGroup")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Facilitator must be a group member")
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/api/groups/treasurer/{id}",
     *     tags={"Member Groups"},
     *     summary="Update treasurer of member group",
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
     *             required={"treasurer_id"},
     *             @OA\Property(property="treasurer_id", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Treasurer updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Treasurer updated successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/MemberGroup")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Treasurer must be a group member")
     * )
     */
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

