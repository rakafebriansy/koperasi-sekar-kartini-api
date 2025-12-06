<?php

namespace App\Http\Controllers;

use App\Http\Resources\WorkAreaResource;
use App\Models\Group;
use App\Models\User;
use App\Models\WorkArea;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class WorkAreaController extends Controller
{

    public function index()
    {
        $workAreas = WorkArea::latest()->get();

        return response()->json([
            'success' => true,
            'data' => WorkAreaResource::collection($workAreas),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:work_areas,name'],
        ], [
            'name.required' => 'Nama wilayah kerja wajib diisi.',
            'name.string' => 'Nama wilayah kerja harus berupa teks.',
            'name.max' => 'Nama wilayah kerja tidak boleh lebih dari 255 karakter.',
            'name.unique' => 'Nama wilayah kerja sudah terdaftar.',
        ]);

        $workArea = WorkArea::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Work area created successfully.',
            'data' => new WorkAreaResource($workArea),
        ], 201);
    }

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
            'name' => ['required', 'string', 'max:255', 'unique:work_areas,name,'.$workArea->id],
        ], [
            'name.required' => 'Nama wilayah kerja wajib diisi.',
            'name.string' => 'Nama wilayah kerja harus berupa teks.',
            'name.max' => 'Nama wilayah kerja tidak boleh lebih dari 255 karakter.',
            'name.unique' => 'Nama wilayah kerja sudah terdaftar.',
        ]);

        $workArea->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Work area updated successfully.',
            'data' => new WorkAreaResource($workArea->fresh()),
        ]);
    }

    public function destroy(string $id)
    {
        $workArea = WorkArea::find($id);

        if (!$workArea) {
            return response()->json([
                'success' => false,
                'message' => 'Work area not found.'
            ], 404);
        }

        $usersCount = User::where('work_area_id', $workArea->id)->count();
        
        $groupCount = Group::where('work_area_id', $workArea->id)->count();

        if ($usersCount > 0 || $groupCount > 0) {
            $usedIn = [];
            if ($usersCount > 0) {
                $usedIn[] = "{$usersCount} pengguna";
            }
            if ($groupCount > 0) {
                $usedIn[] = "{$groupCount} kelompok anggota";
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


