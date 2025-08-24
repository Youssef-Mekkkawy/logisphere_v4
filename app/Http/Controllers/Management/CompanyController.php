<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;


use App\Models\Management\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // 🔥 TEMPORARILY DISABLED FOR TESTING - Re-enable after fixing
        $this->middleware('permission:companies.view')->only(['index', 'show']);
        $this->middleware('permission:companies.create')->only(['create', 'store']);
        $this->middleware('permission:companies.edit')->only(['edit', 'update']);
        $this->middleware('permission:companies.delete')->only(['destroy']);
    }

    /**
     * Display companies with advanced filtering
     */
    public function index(Request $request)
    {
        return view('management.companies.index',);
    }
}
