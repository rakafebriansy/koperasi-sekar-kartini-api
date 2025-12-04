<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Employees",
 *     description="Employee management endpoints"
 * )
 */
class EmployeeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/employees",
     *     tags={"Employees"},
     *     summary="Get all employees",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        // Hanya ambil data dengan role 'employee'
        $employees = User::where('role', 'employee')
            ->with(['workArea', 'memberGroup'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($employees),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/employees",
     *     tags={"Employees"},
     *     summary="Create new employee",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "member_number",
     *                     "identity_number",
     *                     "birth_date",
     *                     "phone_number",
     *                     "address",
     *                     "occupation",
     *                     "identity_card_photo",
     *                     "self_photo",
     *                     "password",
     *                     "work_area_id"
     *                 },
     *                 @OA\Property(property="name", type="string", example="Siti Nurhaliza"),
     *                 @OA\Property(property="member_number", type="string", example="EMP-001", description="Nomor anggota unik"),
     *                 @OA\Property(property="identity_number", type="string", example="3201010101900002", description="NIK unik"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="1990-02-15"),
     *                 @OA\Property(property="phone_number", type="string", example="081234567891", description="Nomor telepon unik"),
     *                 @OA\Property(property="address", type="string", example="Jl. Merdeka No. 456"),
     *                 @OA\Property(property="occupation", type="string", example="Karyawan"),
     *                 @OA\Property(property="identity_card_photo", type="string", format="binary"),
     *                 @OA\Property(property="self_photo", type="string", format="binary"),
     *                 @OA\Property(property="password", type="string", example="password123"),
     *                 @OA\Property(property="work_area_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Employee created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Employee created successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
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
            'identity_card_photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'self_photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'password' => ['required', 'string', 'min:8'],
            'work_area_id' => ['required', 'exists:work_areas,id'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'member_number.required' => 'Nomor Anggota wajib diisi.',
            'member_number.unique' => 'Nomor Anggota sudah terdaftar.',

            'identity_number.required' => 'NIK wajib diisi.',
            'identity_number.unique' => 'NIK sudah terdaftar.',

            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',

            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.unique' => 'Nomor telepon sudah terdaftar.',

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

            'work_area_id.required' => 'Wilayah kerja wajib dipilih.',
            'work_area_id.exists' => 'Wilayah kerja tidak valid.',
        ]);

        $identityCardPhotoPath = $request->file('identity_card_photo')->store('employees/identity_cards', 'public');
        $selfPhotoPath = $request->file('self_photo')->store('employees/photos', 'public');

        $employee = User::create([
            'name' => $validated['name'],
            'member_number' => $validated['member_number'],
            'identity_number' => $validated['identity_number'],
            'birth_date' => $validated['birth_date'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'occupation' => $validated['occupation'],
            'identity_card_photo' => $identityCardPhotoPath,
            'self_photo' => $selfPhotoPath,
            'password' => Hash::make($validated['password']),
            'work_area_id' => $validated['work_area_id'],
            'role' => 'employee',
            'is_verified' => false,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully.',
            'data' => new UserResource($employee->load('workArea')),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/employees/{id}",
     *     tags={"Employees"},
     *     summary="Get employee by ID",
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
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Employee not found.")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $employee = User::with('workArea')->find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        if ($employee->role !== 'employee') {
            return response()->json([
                'success' => false,
                'message' => 'User is not an employee.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new UserResource($employee),
        ]);
    }

    /**
     * @OA\Patch(
     *     path="/api/employees/{id}",
     *     tags={"Employees"},
     *     summary="Update employee",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Siti Nurhaliza"),
     *                 @OA\Property(property="member_number", type="string", example="EMP-001", description="Nomor anggota unik"),
     *                 @OA\Property(property="identity_number", type="string", example="3201010101900002", description="NIK unik"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="1990-02-15"),
     *                 @OA\Property(property="phone_number", type="string", example="081234567891", description="Nomor telepon unik"),
     *                 @OA\Property(property="address", type="string", example="Jl. Merdeka No. 456"),
     *                 @OA\Property(property="occupation", type="string", example="Karyawan"),
     *                 @OA\Property(property="identity_card_photo", type="string", format="binary"),
     *                 @OA\Property(property="self_photo", type="string", format="binary"),
     *                 @OA\Property(property="password", type="string", example="password123"),
     *                 @OA\Property(property="work_area_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Employee updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Employee updated successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Employee not found.")
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $employee = User::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        if ($employee->role !== 'employee') {
            return response()->json([
                'success' => false,
                'message' => 'User is not an employee.'
            ], 404);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'member_number' => ['sometimes', 'required', 'string', 'unique:users,member_number,' . $employee->id],
            'identity_number' => ['sometimes', 'required', 'string', 'unique:users,identity_number,' . $employee->id],
            'birth_date' => ['sometimes', 'required', 'date'],
            'phone_number' => ['sometimes', 'required', 'string', 'unique:users,phone_number,' . $employee->id],
            'address' => ['sometimes', 'required', 'string'],
            'occupation' => ['sometimes', 'required', 'string'],
            'identity_card_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'self_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'password' => ['sometimes', 'required', 'string', 'min:8'],
            'work_area_id' => ['sometimes', 'required', 'exists:work_areas,id'],
        ]);

        $updateData = [];
        
        if (isset($validated['name'])) {
            $updateData['name'] = $validated['name'];
        }
        if (isset($validated['member_number'])) {
            $updateData['member_number'] = $validated['member_number'];
        }
        if (isset($validated['identity_number'])) {
            $updateData['identity_number'] = $validated['identity_number'];
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
        if (isset($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
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
            'data' => new UserResource($employee->fresh(['workArea', 'memberGroup'])),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/employees/{id}",
     *     tags={"Employees"},
     *     summary="Delete employee",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Employee deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Employee deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Employee not found.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $employee = User::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        if ($employee->role !== 'employee') {
            return response()->json([
                'success' => false,
                'message' => 'User is not an employee.'
            ], 404);
        }

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

