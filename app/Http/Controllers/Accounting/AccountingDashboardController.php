<?php

namespace App\Http\Controllers\Management\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Management\Account\{Invoice, Payment, Expense, EmployeeAdvance};


class AccountingDashboardController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:accounting.view')->only(['index', 'show']);
        $this->middleware('permission:accounting.create')->only(['create', 'store']);
        $this->middleware(middleware: 'permission:accounting.edit')->only(['edit', 'update']);
        $this->middleware('permission:accounting.delete')->only(['destroy']);
    }

    public function index()
    {

        return view('accounting.index');
    }
}
