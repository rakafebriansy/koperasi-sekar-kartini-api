<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Models\Group;
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
            'name' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'unique:groups,number'],
            'description' => ['nullable'],
            'shared_liability_fund_amount' => ['required'],
            'group_fund_amount' => ['required'],
            'social_fund_amount' => ['required'],
            'total_shared_liability_fund' => ['required'],
            'total_group_fund' => ['required'],
            'total_social_fund' => ['required'],
            'work_area_id' => ['nullable', 'exists:work_areas,id'],
            'chairman_id' => ['nullable', 'exists:users,id'],
            'facilitator_id' => ['nullable', 'exists:users'],
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
            'name' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', Rule::unique('groups', 'number')->ignore($id)],
            'description' => ['nullable'],
            'shared_liability_fund_amount' => ['nullable'],
            'group_fund_amount' => ['nullable'],
            'social_fund_amount' => ['nullable'],
            'total_shared_liability_fund' => ['nullable'],
            'total_group_fund' => ['nullable'],
            'total_social_fund' => ['nullable'],
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
}
