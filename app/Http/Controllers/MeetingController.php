<?php

namespace App\Http\Controllers;

use App\Http\Resources\MeetingResource;
use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    private $errorMessage = [
        'meeting_type.required' => 'Tipe pertemuan wajib diisi.',
        'meeting_type.in' => 'Tipe pertemuan harus Activity atau Routine Meeting.',
        'date.required' => 'Tanggal wajib diisi.',
        'time.required' => 'Waktu wajib diisi.',
        'location.required' => 'Lokasi wajib diisi.',
        'photo.required' => 'Foto wajib diisi.',
        'description.required' => 'Deskripsi wajib diisi.',
        'group_id.exists' => 'Group tidak valid.',
    ];

    public function index(Request $request)
    {
        $q = Meeting::query();

        if ($request->input('search')) {
            $q->whereRaw('LOWER(description) LIKE ?', ['%' . strtolower($request->input('search')) . '%']);
        }

        $meetings = $q->get();

        return response()->json([
            'success' => true,
            'data' => MeetingResource::collection($meetings),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meeting_type' => ['required', 'in:Activity,Routine Meeting'],
            'date' => ['required', 'date'],
            'time' => ['required'],
            'location' => ['required', 'string'],
            'photo' => ['required', 'string'],
            'description' => ['required', 'string'],
            'group_id' => ['nullable', 'exists:groups,id'],
        ], $this->errorMessage);

        $meeting = Meeting::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Meeting created successfully.',
            'data' => new MeetingResource($meeting),
        ], 201);
    }

    public function show(string $id)
    {
        $meeting = Meeting::find($id);

        if (!$meeting) {
            return response()->json([
                'success' => false,
                'message' => 'Meeting is not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new MeetingResource($meeting),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $meeting = Meeting::find($id);

        if (!$meeting) {
            return response()->json([
                'success' => false,
                'message' => 'Meeting is not found.',
            ], 404);
        }

        $validated = $request->validate([
            'meeting_type' => ['nullable', 'in:Activity,Routine Meeting'],
            'date' => ['nullable', 'date'],
            'time' => ['nullable'],
            'location' => ['nullable', 'string'],
            'photo' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'group_id' => ['nullable', 'exists:groups,id'],
        ], $this->errorMessage);

        $meeting->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Meeting updated successfully.',
            'data' => new MeetingResource($meeting),
        ]);
    }

    public function destroy(string $id)
    {
        $meeting = Meeting::find($id);

        if (!$meeting) {
            return response()->json([
                'success' => false,
                'message' => 'Meeting is not found.',
            ], 404);
        }

        $meeting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meeting deleted successfully.',
        ]);
    }
}
