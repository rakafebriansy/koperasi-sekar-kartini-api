<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private $errorMessage = [
        'name.required' => 'Nama wajib diisi.',
        'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

        'identity_number.required' => 'NIK wajib diisi.',
        'identity_number.unique' => 'NIK sudah terdaftar.',

        'member_number.required' => 'Nomor Anggota wajib diisi.',
        'member_number.unique' => 'Nomor Anggota sudah terdaftar.',

        'birth_date.required' => 'Tanggal lahir wajib diisi.',
        'birth_date.date' => 'Format tanggal lahir tidak valid.',

        'phone_number.required' => 'Nomor telepon wajib diisi.',

        'address.required' => 'Alamat wajib diisi.',

        'occupation.required' => 'Pekerjaan wajib diisi.',

        'identity_card_photo.required' => 'Foto KTP wajib diunggah.',
        'identity_card_photo.image' => 'File foto KTP harus berupa gambar.',
        'identity_card_photo.mimes' => 'Foto KTP harus berformat JPG, JPEG, atau PNG.',
        'identity_card_photo.max' => 'Foto KTP maksimal berukuran 2MB.',

        'self_photo.required' => 'Pas Foto wajib diunggah.',
        'self_photo.image' => 'File Pas Foto harus berupa gambar.',
        'self_photo.mimes' => 'Pas Foto harus berformat JPG, JPEG, atau PNG.',
        'self_photo.max' => 'Pas Foto maksimal berukuran 2MB.',

        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
    ];

    public function index(Request $request)
    {
        $q = User::query();

        if ($request->input('role')) {
            $q = $q->where('role', $request->input('role'));
        }

        if ($request->input('group_id')) {
            $q = $q->where('group_id', $request->input('group_id'));
        }

        if ($request->input('search')) {
            $q = $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->input('search')) . '%']);
        }

        $users = $q->get();

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'member_number' => ['required', 'string', 'unique:users,member_number'],
            'identity_number' => ['required', 'string', 'unique:users,identity_number'],
            'birth_date' => ['required', 'date'],
            'phone_number' => ['required', 'string', 'unique:users,phone_number'],
            'address' => ['required', 'string'],
            'occupation' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'string'],
            'identity_card_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'self_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], $this->errorMessage);

        $identityCardPhotoPath = null;
        $selfPhotoPath = null;
        $memberCardPhotoPath = null;

        if ($request->hasFile('identity_card_photo')) {
            $identityCardPhotoPath = $request->file('identity_card_photo')->store(ucfirst($validated['role']) . 's/identity_cards', 'public');
        }

        if ($request->hasFile('self_photo')) {
            $selfPhotoPath = $request->file('self_photo')->store(ucfirst($validated['role']) . 's/photos', 'public');
        }

        if ($request->hasFile('member_card_photo')) {
            $memberCardPhotoPath = $request->file('member_card_photo')->store(ucfirst($validated['role']) . 's/photos', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'member_number' => $validated['member_number'],
            'identity_number' => $validated['identity_number'],
            'birth_date' => $validated['birth_date'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'role' => $validated['role'],
            'occupation' => $validated['occupation'],
            'identity_card_photo' => $identityCardPhotoPath,
            'self_photo' => $selfPhotoPath,
            'member_card_photo' => $memberCardPhotoPath,
            'work_area_id' => null,
            'is_active' => true,
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => ucfirst($user->role) . ' created successfully.',
            'data' => new UserResource($user),
        ], 201);
    }

    public function show(string $id)
    {
        $user = User::with('workArea')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User is not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User is not found.'
            ], 404);
        }
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'member_number' => ['nullable', 'string', Rule::unique('users', 'member_number')->ignore($id)],
            'identity_number' => ['nullable', 'string', Rule::unique('users', 'identity_number')->ignore($id)],
            'birth_date' => ['nullable', 'date'],
            'phone_number' => ['nullable', 'string', Rule::unique('users', 'phone_number')->ignore($id)],
            'address' => ['nullable', 'string'],
            'occupation' => ['nullable', 'string'],
            'password' => ['nullable', 'string'],
            'identity_card_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'self_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'member_card_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], $this->errorMessage);


        $updateData = $validated;

        if ($request->hasFile('identity_card_photo')) {
            if ($user->identity_card_photo) {
                Storage::disk('public')->delete($user->identity_card_photo);
            }
            $updateData['identity_card_photo'] = $request->file('identity_card_photo')->store(ucfirst($user->role) . 's/identity_cards', 'public');
        }

        if ($request->hasFile('self_photo')) {
            if ($user->self_photo) {
                Storage::disk('public')->delete($user->self_photo);
            }
            $updateData['self_photo'] = $request->file('self_photo')->store(ucfirst($user->role) . 's/photos', 'public');
        }

        if ($request->hasFile('member_card_photo')) {
            if ($user->member_card_photo) {
                Storage::disk('public')->delete($user->member_card_photo);
            }
            $updateData['member_card_photo'] = $request->file('member_card_photo')->store(ucfirst($user->role) . 's/photos', 'public');
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => ucfirst($user->role) . ' updated successfully.',
            'data' => new UserResource($user),
        ]);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User is not found.'
            ], 404);
        }

        if ($user->identity_card_photo) {
            Storage::disk('public')->delete($user->identity_card_photo);
        }

        if ($user->self_photo) {
            Storage::disk('public')->delete($user->self_photo);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => ucfirst($user->role) . 'deleted successfully.',
        ]);
    }

    public function updateGroup(string $memberId, string $groupId)
    {
        $group = Group::find($groupId);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group is not found.'
            ], 404);
        }

        $member = User::find($memberId);

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member is not found.'
            ], 404);
        }

        if ($member->role != 'group_member') {
            return response()->json([
                'success' => false,
                'message' => 'User is not an member.'
            ], 404);
        }

        $member->update(['group_id' => $group->id]);

        return response()->json([
            'success' => true,
            'message' => 'Group member added successfully.',
            'data' => new UserResource($member),
        ]);
    }

    public function unlistedMembers(Request $request)
    {
        $q = User::query()->where('role', 'group_member')->whereNull('group_id');

        if ($request->input('work_area_id')) {
            $q->where('work_area_id', $request->input('work_area_id'));
        }

        if ($request->input('search')) {
            $q = $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->input('search')) . '%']);
        }

        $users = $q->get();

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users),
        ]);
    }

    public function inactiveMembers(Request $request)
    {
        $q = User::query()->where('role', 'group_member')->where('is_active', false);

        if ($request->input('search')) {
            $q = $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->input('search')) . '%']);
        }

        $users = $q->get();

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users),
        ]);
    }

    public function activate(Request $request, string $id)
    {
        $member = User::find($id);

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member is not found.'
            ], 404);
        }

        if ($member->role != 'group_member') {
            return response()->json([
                'success' => false,
                'message' => 'User is not an member.'
            ], 404);
        }

        if ($request->has('is_active')) {

            $member->update(['is_active' => (int) $request->is_active]);
            return response()->json([
                'success' => true,
                'message' => 'Member ' . $request->is_active ? 'activated' : 'deactivated' . ' successfully.',
                'data' => new UserResource($member),
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => "There's no is_active field on payload.",
        ], 400);

    }

}
