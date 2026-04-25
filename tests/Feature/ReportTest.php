<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_report_with_date_filter(): void
    {
        $owner = User::factory()->owner()->create();

        Order::factory()->paid()->create([
            'total_price' => 50000,
            'created_at' => now()->subDay(),
        ]);

        Order::factory()->allDone()->create([
            'total_price' => 70000,
            'created_at' => now()->subMonths(2),
        ]);

        Expense::factory()->create([
            'user_id' => $owner->id,
            'title' => 'Milk Supply',
            'amount' => 20000,
            'expense_date' => now()->subDay()->toDateString(),
        ]);

        $response = $this->actingAs($owner)->get('/owner/reports?date_from=' . now()->subDays(2)->toDateString() . '&date_to=' . now()->toDateString());

        $response->assertOk();
        $response->assertSee('Milk Supply');
        $response->assertSee('50.000');
        $response->assertDontSee('70.000');
    }

    public function test_owner_can_download_report_pdf(): void
    {
        $owner = User::factory()->owner()->create();

        Order::factory()->paid()->create([
            'total_price' => 50000,
            'created_at' => now()->subDay(),
        ]);

        Expense::factory()->create([
            'user_id' => $owner->id,
            'title' => 'Milk Supply',
            'amount' => 20000,
            'expense_date' => now()->subDay()->toDateString(),
        ]);

        $response = $this->actingAs($owner)->get('/owner/reports/pdf?date_from=' . now()->subDays(2)->toDateString() . '&date_to=' . now()->toDateString());

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader('content-disposition');
    }
}
