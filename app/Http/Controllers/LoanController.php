<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoanResource;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    private $errorMessage = [
        'type.required' => 'Jenis pinjaman wajib diisi.',
        'type.in' => 'Jenis pinjaman tidak valid.',

        'nominal.required' => 'Nominal pinjaman wajib diisi.',

        'year.required' => 'Tahun wajib diisi.',
        'month.required' => 'Bulan wajib diisi.',

        'user_id.required' => 'Anggota wajib dipilih.',
        'user_id.exists' => 'Anggota tidak ditemukan.',
    ];

    private $loanType = [
        'pinjaman_biasa',
        'pinjaman_pengadaan_barang',
        'pinjaman_bbm',
        'pinjaman_bahan_pokok',
        'pinjaman_barang_dagangan',
        'pinjaman_lebaran',
        'pinjaman_spesial',
    ];

    public function index(Request $request)
    {
        $q = Loan::query();

        if ($request->has('member_id')) {
            $q->where('user_id', $request->member_id);
        }

        if ($request->filled('search')) {

            $search = $request->search;


            $dt = Carbon::createFromFormat('m/Y', $search);

            $q->where('year', $dt->year)
                ->where('month', $dt->month);
        }

        $loan = $q->get();

        return response()->json([
            'success' => true,
            'data' => LoanResource::collection($loan),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:' . implode(',', $this->loanType)],
            'nominal' => ['required'],
            'year' => ['required'],
            'month' => ['required'],
            'user_id' => ['required', 'exists:users,id'],
        ], $this->errorMessage);


        $loan = Loan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Loan created successfully.',
            'data' => new LoanResource($loan),
        ], 201);
    }

    public function show(string $id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Loan is not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new LoanResource($loan),
        ]);
    }

    public function sumByMonth(Request $request)
    {
        $q = Loan::query();

        if ($request->year && $request->month) {
            $q->where('year', $request->year)
                ->where('month', $request->month);
        }

        $totalNominal = $q->sum('nominal');

        if ($totalNominal == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Loan not found for the specified month and year.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => (int) $totalNominal
            ,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Loan is not found.'
            ], 404);
        }

        $validated = $request->validate([
            'type' => ['nullable', 'in:' . implode(',', $this->loanType)],
            'nominal' => ['nullable'],
            'year' => ['nullable'],
            'status' => ['nullable'],
            'month' => ['nullable'],
            'user_id' => ['nullable', 'exists:users,id'],
        ], $this->errorMessage);

        $loan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Loan updated successfully.',
            'data' => new LoanResource($loan),
        ]);
    }

    public function destroy(string $id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Loan is not found.'
            ], 404);
        }

        $loan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Loan deleted successfully.',
        ]);
    }
}
