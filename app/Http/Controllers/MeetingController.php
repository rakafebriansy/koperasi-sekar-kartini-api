<?php

namespace App\Http\Controllers;

use App\Http\Resources\MeetingResource;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;

class MeetingController extends Controller
{
    private $errorMessage = [
        'name.required' => 'Nama kegiatan wajib diisi.',
        'name.string' => 'Nama kegiatan harus berupa teks.',

        'type.required' => 'Jenis kegiatan wajib dipilih.',
        'type.in' => 'Jenis kegiatan harus berupa "group" atau "coop".',

        'datetime.required' => 'Tanggal dan waktu kegiatan wajib diisi.',

        'location.required' => 'Lokasi kegiatan wajib diisi.',
        'location.string' => 'Lokasi kegiatan harus berupa teks.',

        'description.required' => 'Deskripsi kegiatan wajib diisi.',
        'description.string' => 'Deskripsi kegiatan harus berupa teks.',

        'group_id.exists' => 'Kelompok yang dipilih tidak ditemukan.',

        'user_id.required' => 'User pembuat kegiatan wajib ada.',
        'user_id.exists' => 'User tidak valid atau tidak ditemukan.',
    ];

    public function index(Request $request)
    {
        $q = Meeting::query();
        $user = auth()->user();

        if ($request->has(key: 'search')) {
            $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->input('search')) . '%']);
        }

        if ($user->role === 'group_member') {
            $q->where(function ($query) use ($user) {
                $query->whereNull('group_id')
                    ->orWhere('group_id', $user->group_id);
            });
        }

        if ($request->has('limit')) {
            $q->limit($request->input('limit'));
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
            'name' => ['required', 'string'],
            'type' => ['required', 'in:group,coop'],
            'datetime' => ['required'],
            'location' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'description' => ['required', 'string'],
            'group_id' => ['nullable', 'exists:groups,id'],
        ], $this->errorMessage);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('meetings/photos', 'public');
        }

        $meeting = Meeting::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'datetime' => $validated['datetime'],
            'location' => $validated['location'],
            'photo' => $photoPath,
            'description' => $validated['description'],
            'group_id' => $validated['group_id'] ?? null,
            'user_id' => auth()->id(),
        ]);

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

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string'],
            'type' => ['sometimes', 'required', 'in:group,coop'],
            'datetime' => ['sometimes', 'required'],
            'location' => ['sometimes', 'required', 'string'],
            'photo' => ['sometimes', 'image', 'max:2048'], // optional file
            'description' => ['sometimes', 'required', 'string'],
            'group_id' => ['nullable', 'exists:groups,id'],
        ], $this->errorMessage);

        $photoPath = $meeting->photo;

        if ($request->hasFile('photo')) {
            if ($meeting->photo && \Storage::disk('public')->exists($meeting->photo)) {
                \Storage::disk('public')->delete($meeting->photo);
            }

            $photoPath = $request->file('photo')->store('meetings/photos', 'public');
        }
        $meeting->update([
            'name' => $validated['name'] ?? $meeting->name,
            'type' => $validated['type'] ?? $meeting->type,
            'datetime' => $validated['datetime'] ?? $meeting->datetime,
            'location' => $validated['location'] ?? $meeting->location,
            'photo' => $photoPath,
            'description' => $validated['description'] ?? $meeting->description,
            'group_id' => $validated['group_id'] ?? $meeting->group_id,
            'user_id' => $validated['user_id'] ?? $meeting->user_id,
        ]);

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

    public function upcomingMeetings(Request $request)
    {
        $q = Meeting::query();
        $user = auth()->user();

        $now = Carbon::now();
        $next24Hours = Carbon::now()->addHours(24);

        $q->whereBetween('datetime', [$now, $next24Hours]);

        if ($request->filled('search')) {
            $q->whereRaw(
                'LOWER(name) LIKE ?',
                ['%' . strtolower($request->input('search')) . '%']
            );
        }

        if ($user->role === 'group_member') {
            $q->where(function ($query) use ($user) {
                $query->whereNull('group_id')
                    ->orWhere('group_id', $user->group_id);
            });
        }

        if ($request->filled('limit')) {
            $q->limit((int) $request->input('limit'));
        }

        $meetings = $q
            ->orderBy('datetime', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => MeetingResource::collection($meetings),
        ]);
    }

}
