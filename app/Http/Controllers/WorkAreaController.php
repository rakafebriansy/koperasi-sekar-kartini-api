<?php

namespace App\Http\Controllers;

use App\Models\MemberGroup;
use App\Models\User;
use App\Models\WorkArea;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Work Areas",
 *     description="Work area management endpoints"
 * )
 */
class WorkAreaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/work-areas",
     *     tags={"Work Areas"},
     *     summary="Get all work areas",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/WorkArea"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        $workAreas = WorkArea::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $workAreas,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/work-areas",
     *     tags={"Work Areas"},
     *     summary="Create new work area",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name_work_area"},
     *             @OA\Property(property="name_work_area", type="string", example="Wilayah Kerja A")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Work area created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Work area created successfully.")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_work_area' => ['required', 'string', 'max:255', 'unique:work_areas,name_work_area'],
        ]);

        $workArea = WorkArea::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Work area created successfully.'
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/work-areas/{id}",
     *     tags={"Work Areas"},
     *     summary="Get work area by ID",
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
     *             @OA\Property(property="data", ref="#/components/schemas/WorkArea")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $workArea = WorkArea::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $workArea,
        ]);
    }

    /**
     * @OA\Patch(
     *     path="/api/work-areas/{id}",
     *     tags={"Work Areas"},
     *     summary="Update work area",
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
     *             required={"name_work_area"},
     *             @OA\Property(property="name_work_area", type="string", example="Wilayah Kerja B")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Work area updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Work area updated successfully.")
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $workArea = WorkArea::findOrFail($id);

        $validated = $request->validate([
            'name_work_area' => ['required', 'string', 'max:255', 'unique:work_areas,name_work_area,'.$workArea->id],
        ]);

        $workArea->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Work area updated successfully.'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/work-areas/{id}",
     *     tags={"Work Areas"},
     *     summary="Delete work area",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Work area deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Work area deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Work area cannot be deleted because it is still referenced")
     * )
     */
    public function destroy(string $id)
    {
        $workArea = WorkArea::find($id);

        try {
            $workArea->delete();

            return response()->json([
                'success' => true,
                'message' => 'Work area deleted successfully.',
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'This work area cannot be deleted because it is still referenced by other data.',
                'errors' => [
                    'work_area' => ['This work area has related records and cannot be deleted.']
                ],
            ], 422);
        }
    }
}


