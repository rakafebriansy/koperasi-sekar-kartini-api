<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')
            ->with('workArea')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $employees,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'member_number' => ['required', 'string', 'unique:users,member_number'],
            'nomor_induk_penduduk' => ['required', 'string', 'unique:users,identity_number'],
            'birth_date' => ['required', 'date'],
            'phone_number' => ['required', 'string'],
            'address' => ['required', 'string'],
            'occupation' => ['required', 'string'],
            'identity_card_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'self_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'work_area_id' => ['nullable', 'exists:work_areas,id'],
        ]);

        $identityCardPhotoPath = null;
        $selfPhotoPath = null;

        if ($request->hasFile('identity_card_photo')) {
            $identityCardPhotoPath = $request->file('identity_card_photo')->store('employees/identity_cards', 'public');
        }

        if ($request->hasFile('self_photo')) {
            $selfPhotoPath = $request->file('self_photo')->store('employees/photos', 'public');
        }

        $employee = User::create([
            'name' => $validated['name'],
            'member_number' => $validated['member_number'],
            'identity_number' => $validated['nomor_induk_penduduk'],
            'birth_date' => $validated['birth_date'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'occupation' => $validated['occupation'],
            'identity_card_photo' => $identityCardPhotoPath,
            'self_photo' => $selfPhotoPath,
            'work_area_id' => $validated['work_area_id'] ?? null,
            'role' => 'employee',
            'is_verified' => true,
            'is_active' => true,
            'password' => Hash::make('employee123'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully.',
            'data' => $employee,
        ], 201);
    }

    public function show(string $id)
    {
        $employee = User::where('role', 'employee')
            ->with('workArea')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $employee,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'member_number' => ['sometimes', 'required', 'string', 'unique:users,member_number,' . $employee->id],
            'nomor_induk_penduduk' => ['sometimes', 'required', 'string', 'unique:users,identity_number,' . $employee->id],
            'birth_date' => ['sometimes', 'required', 'date'],
            'phone_number' => ['sometimes', 'required', 'string'],
            'address' => ['sometimes', 'required', 'string'],
            'occupation' => ['sometimes', 'required', 'string'],
            'identity_card_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'self_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'work_area_id' => ['nullable', 'exists:work_areas,id'],
        ]);

        $updateData = [];
        
        if (isset($validated['name'])) {
            $updateData['name'] = $validated['name'];
        }
        if (isset($validated['member_number'])) {
            $updateData['member_number'] = $validated['member_number'];
        }
        if (isset($validated['nomor_induk_penduduk'])) {
            $updateData['identity_number'] = $validated['nomor_induk_penduduk'];
        }
        if (isset($validated['birth_date'])) {
            $updateData['birth_date'] = $validated['birth_date'];
        }
        if (isset($validated['phone_number'])) {
            $updateData['phone_number'] = $validated['phone_number'];
        }
        if (isset($validated['address'])) {
            $updateData['address'] = $validated['address'];
        }
        if (isset($validated['occupation'])) {
            $updateData['occupation'] = $validated['occupation'];
        }
        if (isset($validated['work_area_id'])) {
            $updateData['work_area_id'] = $validated['work_area_id'];
        }

        if ($request->hasFile('identity_card_photo')) {
            if ($employee->identity_card_photo) {
                Storage::disk('public')->delete($employee->identity_card_photo);
            }
            $updateData['identity_card_photo'] = $request->file('identity_card_photo')->store('employees/identity_cards', 'public');
        }

        if ($request->hasFile('self_photo')) {
            if ($employee->self_photo) {
                Storage::disk('public')->delete($employee->self_photo);
            }
            $updateData['self_photo'] = $request->file('self_photo')->store('employees/photos', 'public');
        }

        $employee->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully.',
            'data' => $employee->fresh('workArea'),
        ]);
    }

    public function destroy(string $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        if ($employee->identity_card_photo) {
            Storage::disk('public')->delete($employee->identity_card_photo);
        }

        if ($employee->self_photo) {
            Storage::disk('public')->delete($employee->self_photo);
        }

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully.',
        ]);
    }
}

