<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;

    class AccountingDashboardController extends Controller
{


    public function __construct()
    {
        // dd("Accounting Dashboard __construct");
        $this->middleware('auth');
        $this->middleware('permission:accounting.view')->only(['index', 'show']);
        $this->middleware('permission:accounting.create')->only(['create', 'store']);
        $this->middleware(middleware: 'permission:accounting.edit')->only(['edit', 'update']);
        $this->middleware('permission:accounting.delete')->only(['destroy']);
    }

    public function index()
    {
        // dd("Accounting Dashboard");
        return view('accounting.index');
    }
}


