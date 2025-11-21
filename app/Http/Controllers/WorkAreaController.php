<?php

namespace App\Http\Controllers;

use App\Models\WorkArea;
use Illuminate\Http\Request;

class WorkAreaController extends Controller
{
    public function index()
    {
        $workAreas = WorkArea::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $workAreas,
        ]);
    }

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

    public function show(string $id)
    {
        $workArea = WorkArea::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $workArea,
        ]);
    }

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

    public function destroy(string $id)
    {
        $workArea = WorkArea::findOrFail($id);

        $workArea->delete();

        return response()->json([
            'success' => true,
            'message' => 'Work area deleted successfully.',
        ]);
    }
}


