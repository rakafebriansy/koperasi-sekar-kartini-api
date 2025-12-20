<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportResource;
use App\Models\Group;
use App\Models\Loan;
use App\Models\Report;
use App\Models\Savings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    private $errorMessage = [
        'quarter.required' => 'Triwulan wajib diisi.',
        'quarter.integer' => 'Triwulan harus berupa angka.',
        'quarter.between' => 'Triwulan harus bernilai antara 1 sampai 4.',
        'quarter.unique' => 'Report untuk triwulan dan tahun ini sudah tersedia pada group ini.',

        'year.required' => 'Tahun wajib diisi.',
        'year.integer' => 'Tahun harus berupa angka.',
        'year.digits' => 'Tahun harus terdiri dari 4 digit.',

        'member_growth_in.required' => 'Jumlah anggota masuk wajib diisi.',
        'member_growth_in.integer' => 'Jumlah anggota masuk harus berupa angka.',

        'member_growth_out.required' => 'Jumlah anggota keluar wajib diisi.',
        'member_growth_out.integer' => 'Jumlah anggota keluar harus berupa angka.',

        'administrative_compliance_percentage.required' => 'Persentase kepatuhan administrasi wajib diisi.',
        'administrative_compliance_percentage.integer' => 'Persentase kepatuhan administrasi harus berupa angka.',

        'deposit_compliance_percentage.required' => 'Persentase kepatuhan simpanan wajib diisi.',
        'deposit_compliance_percentage.integer' => 'Persentase kepatuhan simpanan harus berupa angka.',

        'attendance_percentage.required' => 'Persentase kehadiran wajib diisi.',
        'attendance_percentage.integer' => 'Persentase kehadiran harus berupa angka.',

        'organization_final_score_percentage.required' => 'Nilai akhir organisasi wajib diisi.',
        'organization_final_score_percentage.integer' => 'Nilai akhir organisasi harus berupa angka.',

        'loan_participation_pb.required' => 'Partisipasi pinjaman PB wajib diisi.',
        'loan_participation_pb.integer' => 'Partisipasi pinjaman PB harus berupa angka.',

        'loan_participation_bbm.required' => 'Partisipasi pinjaman BBM wajib diisi.',
        'loan_participation_bbm.integer' => 'Partisipasi pinjaman BBM harus berupa angka.',

        'loan_participation_store.required' => 'Partisipasi pinjaman toko wajib diisi.',
        'loan_participation_store.integer' => 'Partisipasi pinjaman toko harus berupa angka.',

        'cash_participation.required' => 'Partisipasi kas wajib diisi.',
        'cash_participation.integer' => 'Partisipasi kas harus berupa angka.',

        'cash_participation_percentage.required' => 'Persentase partisipasi kas wajib diisi.',
        'cash_participation_percentage.integer' => 'Persentase partisipasi kas harus berupa angka.',

        'savings_participation.required' => 'Partisipasi simpanan wajib diisi.',
        'savings_participation.integer' => 'Partisipasi simpanan harus berupa angka.',

        'savings_participation_percentage.required' => 'Persentase partisipasi simpanan wajib diisi.',
        'savings_participation_percentage.integer' => 'Persentase partisipasi simpanan harus berupa angka.',

        'meeting_deposit_percentage.required' => 'Persentase simpanan rapat wajib diisi.',
        'meeting_deposit_percentage.integer' => 'Persentase simpanan rapat harus berupa angka.',

        'loan_balance_pb.required' => 'Saldo pinjaman PB wajib diisi.',
        'loan_balance_pb.integer' => 'Saldo pinjaman PB harus berupa angka.',

        'loan_balance_bbm.required' => 'Saldo pinjaman BBM wajib diisi.',
        'loan_balance_bbm.integer' => 'Saldo pinjaman BBM harus berupa angka.',

        'loan_balance_store.required' => 'Saldo pinjaman toko wajib diisi.',
        'loan_balance_store.integer' => 'Saldo pinjaman toko harus berupa angka.',

        'financial_final_score_percentage.required' => 'Nilai akhir keuangan wajib diisi.',
        'financial_final_score_percentage.integer' => 'Nilai akhir keuangan harus berupa angka.',

        'receivable_score.required' => 'Nilai piutang wajib diisi.',
        'receivable_score.integer' => 'Nilai piutang harus berupa angka.',

        'combined_final_score_percentage.required' => 'Nilai akhir gabungan wajib diisi.',
        'combined_final_score_percentage.integer' => 'Nilai akhir gabungan harus berupa angka.',

        'criteria.required' => 'Kriteria penilaian wajib dipilih.',
        'criteria.in' => 'Kriteria penilaian tidak valid.',
    ];


    public function index(Request $request, string $groupId)
    {
        $q = Report::query()->where('group_id', $groupId);

        if ($request->filled('search')) {
            $year = (int) $request->input('search');

            $q->where('year', $year);
        }

        $reports = $q->get();

        Log::info($reports->first());

        return response()->json([
            'success' => true,
            'data' => ReportResource::collection($reports),
        ]);
    }

    public function store(Request $request, string $groupId)
    {
        $validated = $request->validate([
            'quarter' => [
                'required',
                'integer',
                'between:1,4',
                Rule::unique('reports')
                    ->where(
                        fn($q) => $q
                            ->where('group_id', $groupId)
                            ->where('year', $request->year)
                    ),

            ],
            'year' => [
                'required',
                'integer',
                'digits:4',
            ],
            'member_growth_in' => ['required', 'integer'],
            'member_growth_out' => ['required', 'integer'],
            'administrative_compliance_percentage' => ['required', 'integer'],
            'deposit_compliance_percentage' => ['required', 'integer'],
            'attendance_percentage' => ['required', 'integer'],
            'organization_final_score_percentage' => ['required', 'integer'],
            'loan_participation_pb' => ['required', 'integer'],
            'loan_participation_bbm' => ['required', 'integer'],
            'loan_participation_store' => ['required', 'integer'],
            'cash_participation' => ['required', 'integer'],
            'cash_participation_percentage' => ['required', 'integer'],
            'savings_participation' => ['required', 'integer'],
            'savings_participation_percentage' => ['required', 'integer'],
            'meeting_deposit_percentage' => ['required', 'integer'],
            'loan_balance_pb' => ['required', 'integer'],
            'loan_balance_bbm' => ['required', 'integer'],
            'loan_balance_store' => ['required', 'integer'],
            'financial_final_score_percentage' => ['required', 'integer'],
            'receivable_score' => ['required', 'integer'],
            'combined_final_score_percentage' => ['required', 'integer'],
            'criteria' => ['required', 'in:sangat_baik,baik,cukup,kurang,sangat_kurang'],
        ], $this
                ->errorMessage);

        $group = Group::find($groupId);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found.'
            ], 404);
        }

        $groupCount = $group->members()->count();
        $groupCountBefore = $groupCount - $validated['member_growth_in'] + $validated['member_growth_out'];
        $groupCountDif = $groupCountBefore > 0
            ? ($groupCount / $groupCountBefore) * 100
            : 0;

        $validated['member_growth_in_percentage'] = $groupCount > 0 ?
            floor(($validated['member_growth_in'] / $groupCount) * 100) : 0;

        $validated['member_growth_out_percentage'] = $groupCount > 0 ?
            floor(($validated['member_growth_out'] / $groupCount) * 100) : 0;

        $validated['group_member_total'] = $groupCount;
        $validated['group_member_total_percentage'] = $groupCountDif < 100 ? -(100 - $groupCountDif) : $groupCountDif - 100;
        $validated['group_id'] = (int) $groupId;

        $report = Report::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Report created successfully.',
            'data' => new ReportResource($report),
        ], 201);
    }

    public function show(string $groupId, string $id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report is not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ReportResource($report),
        ]);
    }

    public function update(Request $request, string $groupId, string $id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report is not found.'
            ], 404);
        }

        $validated = $request->validate([
            'member_growth_in' => ['nullable', 'integer'],
            'member_growth_in_percentage' => ['nullable', 'integer'],
            'member_growth_out' => ['nullable', 'integer'],
            'member_growth_out_percentage' => ['nullable', 'integer'],
            'group_member_total' => ['nullable', 'integer'],
            'group_member_total_percentage' => ['nullable', 'integer'],
            'administrative_compliance_percentage' => ['nullable', 'integer'],
            'deposit_compliance_percentage' => ['nullable', 'integer'],
            'attendance_percentage' => ['nullable', 'integer'],
            'organization_final_score_percentage' => ['nullable', 'integer'],
            'loan_participation_pb' => ['nullable', 'integer'],
            'loan_participation_bbm' => ['nullable', 'integer'],
            'loan_participation_store' => ['nullable', 'integer'],
            'cash_participation' => ['nullable', 'integer'],
            'cash_participation_percentage' => ['nullable', 'integer'],
            'savings_participation' => ['nullable', 'integer'],
            'savings_participation_percentage' => ['nullable', 'integer'],
            'meeting_deposit_percentage' => ['nullable', 'integer'],
            'loan_balance_pb' => ['nullable', 'integer'],
            'loan_balance_bbm' => ['nullable', 'integer'],
            'loan_balance_store' => ['nullable', 'integer'],
            'receivable_score' => ['nullable', 'integer'],
            'financial_final_score_percentage' => ['nullable', 'integer'],
            'combined_final_score_percentage' => ['nullable', 'integer'],
            'criteria' => ['nullable', 'in:sangat_baik,baik,cukup,kurang,sangat_kurang'],
        ], $this->errorMessage);

        $report->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Report updated successfully.',
            'data' => new ReportResource($report),
        ]);
    }

    public function destroy(string $groupId, string $id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report is not found.'
            ], 404);
        }

        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Report deleted successfully.',
        ]);
    }

    public function memberGrowth()
    {
        $data = DB::table('reports')
            ->select(
                'year',
                DB::raw('AVG(group_member_total) as total')
            )
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function dashboardStats()
    {
        $now = Carbon::now();
        $quarter = ceil($now->month / 3);

        $totalActiveMembers = User::where('role', 'group_member')
            ->where('is_active', true)
            ->count();

        $totalInactiveMembers = User::where('role', 'group_member')
            ->where('is_active', false)
            ->count();

        $totalMembers = $totalActiveMembers + $totalInactiveMembers;

        $membersJoinedThisQuarter = Report::where('quarter', $quarter)
            ->where('year', $now->year)
            ->sum('member_growth_in');

        $membersLeftThisQuarter = Report::where('quarter', $quarter)
            ->where('year', $now->year)
            ->sum('member_growth_out');

        $joinedPercentage = $totalMembers > 0
            ? round(($membersJoinedThisQuarter / $totalMembers) * 100, 2)
            : 0;

        $leftPercentage = $totalMembers > 0
            ? round(($membersLeftThisQuarter / $totalMembers) * 100, 2)
            : 0;

        $usersSavedThisMonth = Savings::where('month', $now->month)
            ->where('year', $now->year)
            ->distinct('user_id')
            ->count('user_id');

        $usersWithoutSavingThisMonth = max(
            $totalActiveMembers - $usersSavedThisMonth,
            0
        );

        $totalSavingsThisMonth = Savings::where('month', $now->month)
            ->where('year', $now->year)
            ->sum('nominal');

        $savingPercentage = $totalActiveMembers > 0
            ? round(($usersSavedThisMonth / $totalActiveMembers) * 100, 2)
            : 0;

        $usersWithUnpaidLoanThisMonth = Loan::where('month', $now->month)
            ->where('year', $now->year)
            ->where('status', 'unpaid')
            ->distinct('user_id')
            ->count('user_id');

        $totalUnpaidLoanThisMonth = Loan::where('month', $now->month)
            ->where('year', $now->year)
            ->where('status', 'unpaid')
            ->sum('nominal');

        $loanPercentage = $totalActiveMembers > 0
            ? round(($usersWithUnpaidLoanThisMonth / $totalActiveMembers) * 100, 2)
            : 0;
        return response()->json([
            'success' => true,
            'data' => [
                'meta' => [
                    'year' => $now->year,
                    'quarter' => $quarter,
                ],

                'member' => [
                    'joined_this_quarter' => (int) $membersJoinedThisQuarter,
                    'joined_percentage' => $joinedPercentage,

                    'left_this_quarter' => (int) $membersLeftThisQuarter,
                    'left_percentage' => $leftPercentage,

                    'total_active_members' => $totalActiveMembers,
                    'total_inactive_members' => $totalInactiveMembers,
                ],

                'savings' => [
                    'users_not_saved_this_month' => $usersWithoutSavingThisMonth,
                    'total_savings_this_month' => (int) $totalSavingsThisMonth,
                    'saving_percentage' => $savingPercentage,
                ],

                'loan' => [
                    'users_unpaid_loan_this_month' => $usersWithUnpaidLoanThisMonth,
                    'total_unpaid_loan_this_month' => (int) $totalUnpaidLoanThisMonth,
                    'loan_percentage' => $loanPercentage,
                ],
            ],
        ]);
    }

}
