<?php

namespace App\Http\Controllers;

use App\Http\Resources\SavingsResource;
use App\Models\Savings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavingsController extends Controller
{
    private $errorMessage = [
        'type.required' => 'Jenis simpanan wajib diisi.',
        'type.in' => 'Jenis simpanan tidak valid.',

        'nominal.required' => 'Nominal simpanan wajib diisi.',

        'year.required' => 'Tahun wajib diisi.',
        'month.required' => 'Bulan wajib diisi.',

        'user_id.required' => 'Anggota wajib dipilih.',
        'user_id.exists' => 'Anggota tidak ditemukan.',
    ];

    private $savingsType = [
        'simpanan_pokok',
        'simpanan_wajib',
        'simpanan_wajib_khusus',
        'simpanan_sukarela',
        'simpanan_bersama',
        'simpanan_berjangka',
        'simpanan_hari_raya',
        'simpanan_hari_tua',
        'simpanan_rekreasi',
    ];

    public function index(Request $request)
    {
        $q = Savings::query();

        if($request->has('member_id'))  {
            $q->where('user_id', $request->member_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $dt = Carbon::createFromFormat('m/Y', $search);

            $q->where('year', $dt->year)
                ->where('month', $dt->month);
        }

        $savingsList = $q->get();

        return response()->json([
            'success' => true,
            'data' => SavingsResource::collection($savingsList),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:' . implode(',', $this->savingsType)],
            'nominal' => ['required'],
            'year' => ['required'],
            'month' => ['required'],
            'user_id' => ['required', 'exists:users,id'],
        ], $this->errorMessage);


        $savings = Savings::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Savings created successfully.',
            'data' => new SavingsResource($savings),
        ], 201);
    }

    public function show(string $id)
    {
        $savings = Savings::find($id);

        if (!$savings) {
            return response()->json([
                'success' => false,
                'message' => 'Savings is not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new SavingsResource($savings),
        ]);
    }

    public function sumByMonth(Request $request)
    {
        $q = Savings::query();

        if ($request->year && $request->month) {
            $q->where('year', $request->year)
                ->where('month', $request->month);
        }

        $totalNominal = $q->sum('nominal');

        if ($totalNominal == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Savings not found for the specified month and year.'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => (int) $totalNominal
        ]);
    }

    public function update(Request $request, string $id)
    {
        $savings = Savings::find($id);

        if (!$savings) {
            return response()->json([
                'success' => false,
                'message' => 'Savings is not found.'
            ], 404);
        }

        $validated = $request->validate([
            'type' => ['nullable', 'in:' . implode(',', $this->savingsType)],
            'nominal' => ['nullable'],
            'year' => ['nullable'],
            'month' => ['nullable'],
            'user_id' => ['nullable', 'exists:users,id'],
        ], $this->errorMessage);

        $savings->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Savings updated successfully.',
            'data' => new SavingsResource($savings),
        ]);
    }

    public function destroy(string $id)
    {
        $savings = Savings::find($id);

        if (!$savings) {
            return response()->json([
                'success' => false,
                'message' => 'Savings is not found.'
            ], 404);
        }

        $savings->delete();

        return response()->json([
            'success' => true,
            'message' => 'Savings deleted successfully.',
        ]);
    }

    public function distribution()
    {
        $data = DB::table('savings')
            ->select(
                'type',
                DB::raw('SUM(nominal) as total')
            )
            ->groupBy('type')
            ->orderBy('type')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
