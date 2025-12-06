<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    private $errorMessage = [
        'name.required' => 'Nama kelompok wajib diisi.',
        'name.string' => 'Nama kelompok harus berupa teks.',
        'name.max' => 'Nama kelompok maksimal 255 karakter.',

        'number.required' => 'Nomor kelompok wajib diisi.',
        'number.unique' => 'Nomor kelompok sudah digunakan.',
        'number.string' => 'Nomor kelompok harus berupa teks.',

        'shared_liability_fund_amount.required' => 'Dana tanggung renteng wajib diisi.',
        'group_fund_amount.required' => 'Dana kelompok wajib diisi.',
        'social_fund_amount.required' => 'Dana sosial wajib diisi.',

        'total_shared_liability_fund.required' => 'Total dana tanggung renteng wajib diisi.',
        'total_group_fund.required' => 'Total dana kelompok wajib diisi.',
        'total_social_fund.required' => 'Total dana sosial wajib diisi.',

        'work_area_id.exists' => 'Area kerja yang dipilih tidak valid.',
        'chairman_id.exists' => 'Ketua yang dipilih tidak valid.',
        'facilitator_id.exists' => 'Fasilitator yang dipilih tidak valid.',
    ];

    public function index(Request $request)
    {
        $q = Group::query();

        if ($request->input('search')) {
            $q = $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->input('search')) . '%']);
        }

        $groups = $q->get();

        return response()->json([
            'success' => true,
            'data' => GroupResource::collection($groups),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => ['required', 'unique:groups,number'],
            'description' => ['nullable'],
            'work_area_id' => ['required', 'exists:work_areas,id'],
            'chairman_id' => ['nullable', 'exists:users,id'],
            'facilitator_id' => ['nullable', 'exists:users,id'],
        ], $this->errorMessage);


        $group = Group::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Group created successfully.',
            'data' => new GroupResource($group),
        ], 201);
    }

    public function show(string $id)
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group is not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new GroupResource($group),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group is not found.'
            ], 404);
        }

        $validated = $request->validate([
            'number' => ['nullable', 'string', Rule::unique('groups', 'number')->ignore($id)],
            'description' => ['nullable'],
            'is_active' => ['nullable'],
            'work_area_id' => ['nullable', 'exists:work_areas'],
            'chairman_id' => ['nullable', 'exists:users'],
            'facilitator_id' => ['nullable', 'exists:users'],
        ], $this->errorMessage);

        $group->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Group updated successfully.',
            'data' => new GroupResource($group),
        ]);
    }

    public function destroy(string $id)
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group is not found.'
            ], 404);
        }

        $group->delete();

        return response()->json([
            'success' => true,
            'message' => 'Group deleted successfully.',
        ]);
    }

    public function updateChairman(string $groupId, string $chairmanId)
    {
        $group = Group::find($groupId);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group is not found.'
            ], 404);
        }

        $chairman = User::find($chairmanId);

        if (!$chairman) {
            return response()->json([
                'success' => false,
                'message' => 'Member is not found.'
            ], 404);
        }

        if ($chairman->role != 'group_member') {
            return response()->json([
                'success' => false,
                'message' => 'User is not a member.'
            ], 404);
        }

        $group->update(['chairman_id' => $chairman->id]);

        return response()->json([
            'success' => true,
            'message' => 'Group\'s chairman updated successfully.',
            'data' => new GroupResource($group),
        ]);
    }
    public function updateFacilitator(string $groupId, string $facilitatorId)
    {
        $group = Group::find($groupId);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group is not found.'
            ], 404);
        }

        $facilitator = User::find($facilitatorId);

        if (!$facilitator) {
            return response()->json([
                'success' => false,
                'message' => 'Member is not found.'
            ], 404);
        }

        if ($facilitator->role != 'employee') {
            return response()->json([
                'success' => false,
                'message' => 'User is not an employee.'
            ], 404);
        }

        $group->update(['facilitator_id' => $facilitator->id]);

        return response()->json([
            'success' => true,
            'message' => 'Group\'s facilitator updated successfully.',
            'data' => new GroupResource($group),
        ]);
    }
}
