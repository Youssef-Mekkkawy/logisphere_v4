<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager')->except(['index', 'show']);
    }

    /**
     * Display companies with advanced filtering
     */
    public function index(Request $request)
    {
        $query = Company::query();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company_code', 'like', "%{$search}%");
            });
        }

        $companies = $query->orderBy('name')->paginate(20);

        return view('companies.index', compact('companies'));
    }

    /**
     * Store new company with validation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Client,Supplier',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'nullable|string',
            'address' => 'required|string',
            'contact_person' => 'required|string',
            'tax_number' => 'nullable|string|unique:companies,tax_number',
            'service_type' => 'nullable|string',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|integer|min:0'
        ]);

        try {
            $validated['company_code'] = $this->generateCompanyCode($validated['type']);
            $validated['status'] = 'Active';

            $company = Company::create($validated);

            return redirect()->route('companies.show', $company)
                ->with('success', 'Company created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create company: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique company code
     */
    private function generateCompanyCode($type)
    {
        $prefix = $type === 'Client' ? 'CLT' : 'SUP';
        $lastCode = Company::where('company_code', 'like', $prefix . '%')
            ->orderBy('company_code', 'desc')
            ->first();

        if ($lastCode) {
            $lastNumber = intval(substr($lastCode->company_code, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function search(Request $request)
    {
        $query = Company::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return response()->json([
            'companies' => $query->limit(10)->get()
        ]);
    }

    public function getMetrics(Company $company)
    {
        $companyService = app(\App\Services\CompanyService::class);
        return response()->json($companyService->getCompanyMetrics($company));
    }
}
