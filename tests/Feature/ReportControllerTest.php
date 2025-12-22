<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Report;
use App\Models\User;
use App\Models\WorkArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $member;
    private $workArea;
    private $group;

    public function setUp(): void
    {
        parent::setUp();

        $this->workArea = WorkArea::create([
            'name' => 'Area Test',
        ]);

        // Admin user
        $this->admin = User::create([
            'name' => 'Admin Test',
            'identity_number' => '9876543210',
            'birth_date' => '1990-01-01',
            'phone_number' => '081234567891',
            'address' => 'Jl. Admin No.1',
            'occupation' => 'Admin',
            'role' => 'admin',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        // Member user
        $this->member = User::create([
            'name' => 'Member Test',
            'identity_number' => '1234567890',
            'birth_date' => '1990-01-01',
            'phone_number' => '081234567890',
            'address' => 'Jl. Test No.1',
            'occupation' => 'Wiraswasta',
            'role' => 'group_member',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        // Group
        $this->group = Group::create([
            'number' => 1,
            'description' => 'Lorem ipsum dolor sit amet',
            'work_area_id' => $this->workArea->id
        ]);


        $this->actingAs($this->admin, 'sanctum');
    }

    /** @test */
    public function index_returns_reports_for_group()
    {
        $report = Report::create([
            'quarter' => 1,
            'year' => 2025,

            'member_growth_in' => 2,
            'member_growth_in_percentage' => 10.0,

            'member_growth_out' => 1,
            'member_growth_out_percentage' => 5.0,

            'group_member_total' => 20,
            'group_member_total_percentage' => 100.0,

            'administrative_compliance_percentage' => 90.0,
            'deposit_compliance_percentage' => 85.0,
            'attendance_percentage' => 88.0,
            'organization_final_score_percentage' => 87.0,

            'loan_participation_pb' => 5,
            'loan_participation_bbm' => 4,
            'loan_participation_store' => 3,

            'cash_participation' => 10,
            'cash_participation_percentage' => 50.0,

            'savings_participation' => 15,
            'savings_participation_percentage' => 75.0,

            'meeting_deposit_percentage' => 80.0,

            'loan_balance_pb' => 0,
            'loan_balance_bbm' => 0,
            'loan_balance_store' => 0,

            'receivable_score' => 90.0,
            'financial_final_score_percentage' => 88.0,
            'combined_final_score_percentage' => 89.0,

            'criteria' => 'baik',

            'group_id' => $this->group->id,
        ]);

        $response = $this->getJson("/api/groups/{$this->group->id}/reports");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    ['id' => $report->id]
                ]
            ]);
    }

    /** @test */
    public function store_creates_report()
    {
        $payload = [
            'quarter' => 1,
            'year' => 2025,
            'member_growth_in' => 3,
            'member_growth_out' => 1,
            'administrative_compliance_percentage' => 80,
            'deposit_compliance_percentage' => 90,
            'attendance_percentage' => 85,
            'organization_final_score_percentage' => 88,
            'loan_participation_pb' => 10,
            'loan_participation_bbm' => 5,
            'loan_participation_store' => 7,
            'cash_participation' => 15,
            'cash_participation_percentage' => 80,
            'savings_participation' => 20,
            'savings_participation_percentage' => 75,
            'meeting_deposit_percentage' => 70,
            'loan_balance_pb' => 50000,
            'loan_balance_bbm' => 20000,
            'loan_balance_store' => 30000,
            'financial_final_score_percentage' => 85,
            'receivable_score' => 90,
            'combined_final_score_percentage' => 87,
            'criteria' => 'baik',
        ];

        $response = $this->postJson("/api/groups/{$this->group->id}/reports", $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Report created successfully.',
                'data' => ['quarter' => 1, 'year' => 2025],
            ]);

        $this->assertDatabaseHas('reports', [
            'group_id' => $this->group->id,
            'quarter' => 1,
            'year' => 2025,
        ]);
    }

    /** @test */
    public function show_returns_single_report()
    {
        $report = Report::create([
            'quarter' => 1,
            'year' => 2025,

            'member_growth_in' => 2,
            'member_growth_in_percentage' => 10.0,

            'member_growth_out' => 1,
            'member_growth_out_percentage' => 5.0,

            'group_member_total' => 20,
            'group_member_total_percentage' => 100.0,

            'administrative_compliance_percentage' => 90.0,
            'deposit_compliance_percentage' => 85.0,
            'attendance_percentage' => 88.0,
            'organization_final_score_percentage' => 87.0,

            'loan_participation_pb' => 5,
            'loan_participation_bbm' => 4,
            'loan_participation_store' => 3,

            'cash_participation' => 10,
            'cash_participation_percentage' => 50.0,

            'savings_participation' => 15,
            'savings_participation_percentage' => 75.0,

            'meeting_deposit_percentage' => 80.0,

            'loan_balance_pb' => 0,
            'loan_balance_bbm' => 0,
            'loan_balance_store' => 0,

            'receivable_score' => 90.0,
            'financial_final_score_percentage' => 88.0,
            'combined_final_score_percentage' => 89.0,

            'criteria' => 'baik',

            'group_id' => $this->group->id,
        ]);

        $response = $this->getJson("/api/groups/{$this->group->id}/reports/{$report->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['id' => $report->id]
            ]);
    }

    /** @test */
    public function update_modifies_report()
    {
        $report = Report::create([
            'quarter' => 1,
            'year' => 2025,

            'member_growth_in' => 2,
            'member_growth_in_percentage' => 10.0,

            'member_growth_out' => 1,
            'member_growth_out_percentage' => 5.0,

            'group_member_total' => 20,
            'group_member_total_percentage' => 100.0,

            'administrative_compliance_percentage' => 90.0,
            'deposit_compliance_percentage' => 85.0,
            'attendance_percentage' => 88.0,
            'organization_final_score_percentage' => 87.0,

            'loan_participation_pb' => 5,
            'loan_participation_bbm' => 4,
            'loan_participation_store' => 3,

            'cash_participation' => 10,
            'cash_participation_percentage' => 50.0,

            'savings_participation' => 15,
            'savings_participation_percentage' => 75.0,

            'meeting_deposit_percentage' => 80.0,

            'loan_balance_pb' => 0,
            'loan_balance_bbm' => 0,
            'loan_balance_store' => 0,

            'receivable_score' => 90.0,
            'financial_final_score_percentage' => 88.0,
            'combined_final_score_percentage' => 89.0,

            'criteria' => 'baik',

            'group_id' => $this->group->id,
        ]);

        $payload = [
            'member_growth_in' => 10,
            'attendance_percentage' => 95,
        ];

        $response = $this->putJson("/api/groups/{$this->group->id}/reports/{$report->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'member_growth_in' => 10,
                    'attendance_percentage' => 95,
                ]
            ]);

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'member_growth_in' => 10,
            'attendance_percentage' => 95,
        ]);
    }

    /** @test */
    public function destroy_deletes_report()
    {
        $report = Report::create([
            'quarter' => 1,
            'year' => 2025,

            'member_growth_in' => 2,
            'member_growth_in_percentage' => 10.0,

            'member_growth_out' => 1,
            'member_growth_out_percentage' => 5.0,

            'group_member_total' => 20,
            'group_member_total_percentage' => 100.0,

            'administrative_compliance_percentage' => 90.0,
            'deposit_compliance_percentage' => 85.0,
            'attendance_percentage' => 88.0,
            'organization_final_score_percentage' => 87.0,

            'loan_participation_pb' => 5,
            'loan_participation_bbm' => 4,
            'loan_participation_store' => 3,

            'cash_participation' => 10,
            'cash_participation_percentage' => 50.0,

            'savings_participation' => 15,
            'savings_participation_percentage' => 75.0,

            'meeting_deposit_percentage' => 80.0,

            'loan_balance_pb' => 0,
            'loan_balance_bbm' => 0,
            'loan_balance_store' => 0,

            'receivable_score' => 90.0,
            'financial_final_score_percentage' => 88.0,
            'combined_final_score_percentage' => 89.0,

            'criteria' => 'baik',

            'group_id' => $this->group->id,
        ]);

        $response = $this->deleteJson("/api/groups/{$this->group->id}/reports/{$report->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Report deleted successfully.',
            ]);

        $this->assertDatabaseMissing('reports', [
            'id' => $report->id,
        ]);
    }
}
