<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        return view('owner.reports.index', $this->reportData($request));
    }

    public function exportPdf(Request $request): Response
    {
        $data = $this->reportData($request);
        $filename = sprintf(
            'brewlang-report-%s-to-%s.pdf',
            $data['date_from'],
            $data['date_to']
        );

        $pdf = Pdf::loadView('owner.reports.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    private function reportData(Request $request): array
    {
        $date_from = $request->query('date_from', now()->startOfMonth()->toDateString());
        $date_to = $request->query('date_to', now()->endOfMonth()->toDateString());

        $ordersQuery = Order::whereIn('status', ['paid', 'in_progress', 'all_done'])
            ->whereDate('created_at', '>=', $date_from)
            ->whereDate('created_at', '<=', $date_to);

        $expensesQuery = Expense::whereDate('expense_date', '>=', $date_from)
            ->whereDate('expense_date', '<=', $date_to);

        $total_income = $ordersQuery->sum('total_price');
        $total_expenses = $expensesQuery->sum('amount');

        return [
            'date_from' => $date_from,
            'date_to' => $date_to,
            'total_income' => $total_income,
            'total_expenses' => $total_expenses,
            'orders' => $ordersQuery->orderBy('created_at', 'desc')->get(),
            'expenses' => $expensesQuery->orderBy('expense_date', 'desc')->get(),
        ];
    }
}
