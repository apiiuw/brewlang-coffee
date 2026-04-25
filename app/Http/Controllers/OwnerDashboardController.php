<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $monthFormatExpression = $this->monthFormatExpression();

        $total_income = Order::whereIn('status', ['paid', 'in_progress', 'all_done'])->sum('total_price');
        $total_expenses = Expense::sum('amount');
        
        $data = [
            'total_orders'   => Order::count(),
            'total_income'   => $total_income,
            'total_expenses' => $total_expenses,
            'net_profit'     => $total_income - $total_expenses,
            'recent_orders'  => Order::latest()->limit(5)->get(),
            'recent_expenses'=> Expense::latest()->limit(5)->get(),
            // Monthly data for chart (last 6 months)
            'monthly_income' => Order::whereIn('status', ['paid', 'in_progress', 'all_done'])
                                   ->selectRaw("{$monthFormatExpression['created_at']} as month, SUM(total_price) as total")
                                   ->where('created_at', '>=', now()->subMonths(6))
                                   ->groupBy('month')
                                   ->pluck('total', 'month'),
            'monthly_expenses' => Expense::selectRaw("{$monthFormatExpression['expense_date']} as month, SUM(amount) as total")
                                   ->where('expense_date', '>=', now()->subMonths(6))
                                   ->groupBy('month')
                                   ->pluck('total', 'month'),
        ];

        return view('owner.dashboard', compact('data'));
    }

    private function monthFormatExpression(): array
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => [
                'created_at' => "strftime('%Y-%m', created_at)",
                'expense_date' => "strftime('%Y-%m', expense_date)",
            ],
            default => [
                'created_at' => "DATE_FORMAT(created_at, '%Y-%m')",
                'expense_date' => "DATE_FORMAT(expense_date, '%Y-%m')",
            ],
        };
    }
}
