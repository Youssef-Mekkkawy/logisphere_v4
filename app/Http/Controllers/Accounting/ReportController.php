<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('accounting.reports.index');
    }

    public function profitLoss(Request $request)
    {
        $period = $request->get('period', 'this_month');
        $data = $this->reportService->generateProfitLoss($period);

        return view('accounting.reports.profit-loss', [
            'data' => $data,
            'period' => $period
        ]);
    }

    public function balanceSheet(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $data = $this->reportService->generateBalanceSheet($date);

        return view('accounting.reports.balance-sheet', [
            'data' => $data,
            'date' => $date
        ]);
    }

    public function cashFlow(Request $request)
    {
        $period = $request->get('period', 'this_month');
        $data = $this->reportService->generateCashFlow($period);

        return view('accounting.reports.cash-flow', [
            'data' => $data,
            'period' => $period
        ]);
    }

    public function aging(Request $request)
    {
        $type = $request->get('type', 'receivables'); // receivables or payables
        $data = $this->reportService->generateAging($type);

        return view('accounting.reports.index', [
            'data' => $data,
            'type' => $type
        ]);
    }

    public function export(Request $request, $type)
    {
        $format = $request->get('format', 'excel');
        $period = $request->get('period', 'this_month');

        return $this->reportService->export($type, $format, $period);
    }
}
