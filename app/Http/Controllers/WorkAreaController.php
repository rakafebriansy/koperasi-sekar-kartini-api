<?php

namespace App\Http\Controllers;

use App\Http\Resources\WorkAreaResource;
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
            'data' => WorkAreaResource::collection($workAreas),
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
     *             @OA\Property(property="message", type="string", example="Work area created successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/WorkArea")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_work_area' => ['required', 'string', 'max:255', 'unique:work_areas,name_work_area'],
        ], [
            'name_work_area.required' => 'Nama wilayah kerja wajib diisi.',
            'name_work_area.string' => 'Nama wilayah kerja harus berupa teks.',
            'name_work_area.max' => 'Nama wilayah kerja tidak boleh lebih dari 255 karakter.',
            'name_work_area.unique' => 'Nama wilayah kerja sudah terdaftar.',
        ]);

        $workArea = WorkArea::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Work area created successfully.',
            'data' => new WorkAreaResource($workArea),
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
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Work area not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Work area not found.")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $workArea = WorkArea::find($id);

        if (!$workArea) {
            return response()->json([
                'success' => false,
                'message' => 'Work area not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new WorkAreaResource($workArea),
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
     *             @OA\Property(property="message", type="string", example="Work area updated successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/WorkArea")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Work area not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Work area not found.")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, string $id)
    {
        $workArea = WorkArea::find($id);

        if (!$workArea) {
            return response()->json([
                'success' => false,
                'message' => 'Work area not found.'
            ], 404);
        }

        $validated = $request->validate([
            'name_work_area' => ['required', 'string', 'max:255', 'unique:work_areas,name_work_area,'.$workArea->id],
        ], [
            'name_work_area.required' => 'Nama wilayah kerja wajib diisi.',
            'name_work_area.string' => 'Nama wilayah kerja harus berupa teks.',
            'name_work_area.max' => 'Nama wilayah kerja tidak boleh lebih dari 255 karakter.',
            'name_work_area.unique' => 'Nama wilayah kerja sudah terdaftar.',
        ]);

        $workArea->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Work area updated successfully.',
            'data' => new WorkAreaResource($workArea->fresh()),
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
     *     @OA\Response(
     *         response=404,
     *         description="Work area not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Work area not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Work area cannot be deleted because it is still referenced",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wilayah kerja tidak dapat dihapus karena masih digunakan."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="work_area",
     *                     type="array",
     *                     @OA\Items(type="string", example="Wilayah kerja ini masih digunakan oleh: 5 pengguna dan 2 kelompok anggota.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $workArea = WorkArea::find($id);

        if (!$workArea) {
            return response()->json([
                'success' => false,
                'message' => 'Work area not found.'
            ], 404);
        }

        // Cek apakah work area masih digunakan di users
        $usersCount = User::where('work_area_id', $workArea->id)->count();
        
        // Cek apakah work area masih digunakan di member groups
        $memberGroupsCount = MemberGroup::where('work_area_id', $workArea->id)->count();

        if ($usersCount > 0 || $memberGroupsCount > 0) {
            $usedIn = [];
            if ($usersCount > 0) {
                $usedIn[] = "{$usersCount} pengguna";
            }
            if ($memberGroupsCount > 0) {
                $usedIn[] = "{$memberGroupsCount} kelompok anggota";
            }

            return response()->json([
                'success' => false,
                'message' => 'Wilayah kerja tidak dapat dihapus karena masih digunakan.',
                'errors' => [
                    'work_area' => [
                        'Wilayah kerja ini masih digunakan oleh: ' . implode(' dan ', $usedIn) . '.'
                    ]
                ],
            ], 422);
        }

        $workArea->delete();

        return response()->json([
            'success' => true,
            'message' => 'Work area deleted successfully.',
        ], 200);
    }
}


