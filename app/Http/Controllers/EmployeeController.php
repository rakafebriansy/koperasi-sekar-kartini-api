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
    public function index(Request $request)
    {
        $q = User::query()->where('role', 'employee');

        if ($request->input('search')) {
            $q = $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->input('search')) . '%']);
        }

        $employees = $q->get();

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
     *                 required={"name","member_number","nomor_induk_penduduk","birth_date","phone_number","address","occupation"},
     *                 @OA\Property(property="name", type="string", example="Siti Nurhaliza"),
     *                 @OA\Property(property="member_number", type="string", example="EMP001"),
     *                 @OA\Property(property="nomor_induk_penduduk", type="string", example="3201010101900002"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="1990-02-15"),
     *                 @OA\Property(property="phone_number", type="string", example="081234567891"),
     *                 @OA\Property(property="address", type="string", example="Jl. Merdeka No. 456"),
     *                 @OA\Property(property="occupation", type="string", example="Karyawan"),
     *                 @OA\Property(property="identity_card_photo", type="string", format="binary"),
     *                 @OA\Property(property="self_photo", type="string", format="binary"),
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
            'phone_number' => ['required', 'string'],
            'address' => ['required', 'string'],
            'occupation' => ['required', 'string'],
            'password' => ['required', 'string'],
            'identity_card_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'self_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], $this->errorMessage);

        $identityCardPhotoPath = null;
        $selfPhotoPath = null;

        if ($request->hasFile('identity_card_photo')) {
            $identityCardPhotoPath = $request->file('identity_card_photo')->store('employees/identity_cards', 'public');
        }

        if ($request->hasFile('self_photo')) {
            $selfPhotoPath = $request->file('self_photo')->store('employees/photos', 'public');
        }

        if ($request->hasFile('member_card_photo')) {
            $memberCardPhotoPath = $request->file('member_card_photo')->store('employees/photos', 'public');
        }

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
            'member_card_photo' => $memberCardPhotoPath,
            'work_area_id' => null,
            'role' => 'employee',
            'is_verified' => true,
            'is_active' => true,
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully.',
            'data' => new UserResource($employee),
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
     *     )
     * )
     */
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
     *                 @OA\Property(property="member_number", type="string", example="EMP001"),
     *                 @OA\Property(property="nomor_induk_penduduk", type="string", example="3201010101900002"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="1990-02-15"),
     *                 @OA\Property(property="phone_number", type="string", example="081234567891"),
     *                 @OA\Property(property="address", type="string", example="Jl. Merdeka No. 456"),
     *                 @OA\Property(property="occupation", type="string", example="Karyawan"),
     *                 @OA\Property(property="identity_card_photo", type="string", format="binary"),
     *                 @OA\Property(property="self_photo", type="string", format="binary"),
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
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'member_number' => ['required', 'string', 'unique:users,member_number,' . $employee->id],
            'identity_number' => ['required', 'string', 'unique:users,identity_number,' . $employee->id],
            'birth_date' => ['required', 'date'],
            'phone_number' => ['required', 'string'],
            'address' => ['required', 'string'],
            'occupation' => ['required', 'string'],
            'password' => ['nullable', 'string'],
            'identity_card_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'self_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'member_card_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], $this->errorMessage);


        $updateData = $validated;

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

        if ($request->hasFile('member_card_photo')) {
            if ($employee->member_card_photo) {
                Storage::disk('public')->delete($employee->member_card_photo);
            }
            $updateData['member_card_photo'] = $request->file('member_card_photo')->store('employees/photos', 'public');
        }

        $employee->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully.',
            'data' => new UserResource($employee),
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
     *     )
     * )
     */
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

