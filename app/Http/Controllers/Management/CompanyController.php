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

        return view('management.companies.index', compact('companies'));
    }

    /**
     * Store new company with validation
     */
    /**
     * Store new company with validation
     */
    /**
     * Store new company with validation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Client,Supplier',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'nullable|string|max:50',
            'address' => 'required|string|max:500',
            'contact_person' => 'required|string|max:255',
            'tax_number' => 'nullable|string|unique:companies,tax_number',
            'service_type' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|integer|min:0|max:365',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:Active,Inactive'
        ]);

        try {
            // Generate company code safely
            $validated['company_code'] = $this->generateCompanyCode($validated['type']);
            $validated['created_by'] = auth()->id();

            $company = Company::create($validated);

            return redirect()->route('management.companies.show', $company)
                ->with('success', 'Company created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create company: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique company code (Safe version)
     */
    private function generateCompanyCode($type)
    {
        $prefix = $type === 'Client' ? 'CLT' : 'SUP';

        try {
            // Check if company_code column exists
            if (Schema::hasColumn('companies', 'company_code')) {
                $lastCode = Company::where('company_code', 'like', $prefix . '%')
                    ->orderBy('company_code', 'desc')
                    ->first();

                if ($lastCode && $lastCode->company_code) {
                    $lastNumber = intval(substr($lastCode->company_code, 3));
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }
            } else {
                // If column doesn't exist, start from 1
                $newNumber = 1;
            }
        } catch (\Exception $e) {
            // If any error occurs, start from 1
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Remove the old generateCompanyCode method since it's now in the model
     */

    /**
     * Generate unique company code
     */


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
    public function create()
    {
        $serviceTypes = [
            'Freight Forwarding' => 'Freight Forwarding',
            'Customs Clearance' => 'Customs Clearance',
            'Warehousing' => 'Warehousing',
            'Transportation' => 'Transportation',
            'Supply Chain Management' => 'Supply Chain Management',
            'Import/Export' => 'Import/Export',
            'Logistics Consulting' => 'Logistics Consulting'
        ];

        $countries = [
            'United Arab Emirates' => 'United Arab Emirates',
            'Saudi Arabia' => 'Saudi Arabia',
            'Egypt' => 'Egypt',
            'Qatar' => 'Qatar',
            'Kuwait' => 'Kuwait',
            'Bahrain' => 'Bahrain',
            'Oman' => 'Oman',
            'Jordan' => 'Jordan',
            'Lebanon' => 'Lebanon',
            'China' => 'China',
            'India' => 'India',
            'Singapore' => 'Singapore',
            'United States' => 'United States',
            'United Kingdom' => 'United Kingdom',
            'Germany' => 'Germany'
        ];

        return view('management.companies.create', compact('serviceTypes', 'countries'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        // Load relationships
        $company->load([
            'shipments' => function ($query) {
                $query->latest()->take(5);
            },
            'bookings' => function ($query) {
                $query->latest()->take(5);
            },
            'invoices' => function ($query) {
                $query->latest()->take(5);
            }
        ]);

        // Get company statistics
        $statistics = [
            'total_shipments' => $company->shipments()->count(),
            'active_shipments' => $company->shipments()->active()->count(),
            'completed_shipments' => $company->shipments()->where('status', 'Delivered')->count(),
            'total_bookings' => $company->bookings()->count(),
            'confirmed_bookings' => $company->bookings()->where('is_confirmed', true)->count(),
            'total_invoices' => $company->invoices()->count(),
            'paid_invoices' => $company->invoices()->where('status', 'Paid')->count(),
            'outstanding_amount' => $company->invoices()->where('status', '!=', 'Paid')->sum('total_amount'),
            'this_month_shipments' => $company->shipments()->whereMonth('created_at', now()->month)->count(),
            'this_year_shipments' => $company->shipments()->whereYear('created_at', now()->year)->count()
        ];

        // Monthly shipment trends for the last 6 months
        $monthlyTrends = $company->shipments()
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->get();

        return view('management.companies.show', compact('company', 'statistics', 'monthlyTrends'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $serviceTypes = [
            'Freight Forwarding' => 'Freight Forwarding',
            'Customs Clearance' => 'Customs Clearance',
            'Warehousing' => 'Warehousing',
            'Transportation' => 'Transportation',
            'Supply Chain Management' => 'Supply Chain Management',
            'Import/Export' => 'Import/Export',
            'Logistics Consulting' => 'Logistics Consulting'
        ];

        $countries = [
            'United Arab Emirates' => 'United Arab Emirates',
            'Saudi Arabia' => 'Saudi Arabia',
            'Egypt' => 'Egypt',
            'Qatar' => 'Qatar',
            'Kuwait' => 'Kuwait',
            'Bahrain' => 'Bahrain',
            'Oman' => 'Oman',
            'Jordan' => 'Jordan',
            'Lebanon' => 'Lebanon',
            'China' => 'China',
            'India' => 'India',
            'Singapore' => 'Singapore',
            'United States' => 'United States',
            'United Kingdom' => 'United Kingdom',
            'Germany' => 'Germany'
        ];

        return view('management.companies.edit', compact('company', 'serviceTypes', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        // Debug: Log the incoming data
        Log::info('Company Update Request Data:', $request->all());
        Log::info('Company Before Update:', $company->toArray());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Client,Supplier',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'phone' => 'nullable|string|max:50',
            'tax_number' => 'nullable|string|max:50|unique:companies,tax_number,' . $company->id,
            'address' => 'required|string|max:500',
            'contact_person' => 'required|string|max:255',
            'service_type' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|integer|min:0|max:365',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:Active,Inactive'
        ]);

        // Debug: Log validated data
        Log::info('Validated Data:', $validated);

        try {
            // Update the company
            $company->update($validated);

            // Debug: Log company after update
            Log::info('Company After Update:', $company->fresh()->toArray());

            return redirect()->route('management.companies.show', $company)
                ->with('success', 'Company updated successfully!');
        } catch (\Exception $e) {
            // Debug: Log any errors
            Log::error('Company Update Error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return back()->withInput()
                ->with('error', 'Failed to update company: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // Check if company has any related records
        $shipmentsCount = $company->shipments()->count();
        $bookingsCount = $company->bookings()->count();
        $invoicesCount = $company->invoices()->count();

        if ($shipmentsCount > 0 || $bookingsCount > 0 || $invoicesCount > 0) {
            return redirect()->route('management.companies.index')
                ->with('error', 'Cannot delete company. It has associated shipments, bookings, or invoices.');
        }

        try {
            $company->delete();

            return redirect()->route('management.companies.index')
                ->with('success', 'Company deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('management.companies.index')
                ->with('error', 'Failed to delete company: ' . $e->getMessage());
        }
    }

    /**
     * Get company performance metrics
     */
    public function performance(Company $company)
    {
        $performanceData = [
            'shipment_metrics' => [
                'total_shipments' => $company->shipments()->count(),
                'on_time_deliveries' => $company->shipments()
                    ->where('status', 'Delivered')
                    ->whereColumn('actual_delivery_date', '<=', 'eta')
                    ->count(),
                'average_shipment_value' => $company->shipments()->avg('value'),
                'total_freight_cost' => $company->shipments()->sum('freight_cost')
            ],
            'financial_metrics' => [
                'total_invoiced' => $company->invoices()->sum('total_amount'),
                'paid_amount' => $company->invoices()->where('status', 'Paid')->sum('total_amount'),
                'outstanding_amount' => $company->invoices()->where('status', '!=', 'Paid')->sum('total_amount'),
                'average_payment_days' => $company->invoices()
                    ->where('status', 'Paid')
                    ->whereNotNull('payment_date')
                    ->selectRaw('AVG(DATEDIFF(payment_date, invoice_date)) as avg_days')
                    ->value('avg_days')
            ],
            'monthly_trends' => $company->shipments()
                ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as shipments, SUM(value) as total_value')
                ->where('created_at', '>=', now()->subYear())
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
                ->get()
        ];

        return view('management.companies.performance', compact('company', 'performanceData'));
    }

    /**
     * Get company shipments
     */
    public function shipments(Company $company, Request $request)
    {
        $query = $company->shipments()->with(['originPort', 'destinationPort', 'employee']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $shipments = $query->orderBy('created_at', 'desc')->paginate(20);
        $statuses = ['Pending', 'In Transit', 'At Port', 'Customs Clearance', 'Delivered', 'Cancelled'];

        return view('management.companies.shipments', compact('company', 'shipments', 'statuses'));
    }

    /**
     * Toggle company status (Active/Inactive)
     */
    public function toggleStatus(Company $company)
    {
        $newStatus = $company->status === 'Active' ? 'Inactive' : 'Active';
        $company->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Company status changed to {$newStatus}!");
    }
}
