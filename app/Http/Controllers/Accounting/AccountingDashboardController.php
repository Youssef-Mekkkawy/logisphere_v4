<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\{Invoice, Payment, Expense, EmployeeAdvance};
use App\Services\AccountingDashboardService;

class AccountingDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(AccountingDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $data = $this->dashboardService->getDashboardData();
        return view('accounting.index', $data);
    }
}