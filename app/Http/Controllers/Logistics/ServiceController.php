<?php


namespace App\Http\Controllers\logistics;

use App\Http\Controllers\Controller;

use App\Models\Service;
// use App\Models\Account; // TODO: Uncomment when Account model exists
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('service_name', 'like', "%{$search}%")
                    ->orWhere('service_code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('service_provider', 'like', "%{$search}%");
            });
        }

        if ($request->filled('service_category')) {
            $query->where('service_category', $request->service_category);
        }

        if ($request->filled('billing_type')) {
            $query->where('billing_type', $request->billing_type);
        }

        if ($request->filled('service_provider')) {
            $query->where('service_provider', $request->service_provider);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_mandatory')) {
            $query->where('is_mandatory', $request->is_mandatory);
        }

        if ($request->filled('is_billable')) {
            $query->where('is_billable', $request->is_billable);
        }

        // Get services with pagination
        // TODO: Uncomment when Account model exists
        // $services = $query->with('account')->orderBy('service_name')->paginate(15)->withQueryString();
        $services = $query->orderBy('service_name')->paginate(15)->withQueryString();

        // Get filter options
        // TODO: Uncomment when Account model exists
        // $accounts = Account::active()->orderBy('name')->get();
        $accounts = collect();

        return view('logistics.services.index', compact('services', 'accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Uncomment when Account model exists
        // $accounts = Account::active()->orderBy('name')->get();
        $accounts = collect();

        return view('logistics.services.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Service::validationRules());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle required_documents array
            if ($request->has('required_documents')) {
                $data['required_documents'] = array_filter($request->get('required_documents', []));
            }

            // Handle applicable_cargo_types array
            if ($request->has('applicable_cargo_types')) {
                $data['applicable_cargo_types'] = array_filter($request->get('applicable_cargo_types', []));
            }

            // Handle rate_tiers JSON
            if ($request->has('rate_tiers') && $request->rate_tiers) {
                $data['rate_tiers'] = json_decode($request->rate_tiers, true);
            }

            Service::create($data);

            DB::commit();

            return redirect()->route('logistics.services.index')
                ->with('success', 'Service created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create service: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        // TODO: Uncomment when Account model exists
        // $service->load('account');

        // Get statistics
        $statistics = $service->getStatistics();

        // Get recent usage
        // TODO: Implement when ShipmentService model exists
        $recentUsage = collect(); // $service->shipmentServices()->with(['shipment.company'])->latest()->take(10)->get();

        // Get top clients
        $topClients = $service->getTopClients(5);

        return view('logistics.services.show', compact('service', 'statistics', 'recentUsage', 'topClients'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        // TODO: Uncomment when Account model exists
        // $accounts = Account::active()->orderBy('name')->get();
        $accounts = collect();

        return view('logistics.services.edit', compact('service', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), Service::validationRules($service->id));

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle required_documents array
            if ($request->has('required_documents')) {
                $data['required_documents'] = array_filter($request->get('required_documents', []));
            }

            // Handle applicable_cargo_types array
            if ($request->has('applicable_cargo_types')) {
                $data['applicable_cargo_types'] = array_filter($request->get('applicable_cargo_types', []));
            }

            // Handle rate_tiers JSON
            if ($request->has('rate_tiers') && $request->rate_tiers) {
                $data['rate_tiers'] = json_decode($request->rate_tiers, true);
            }

            $service->update($data);

            DB::commit();

            return redirect()->route('logistics.services.show', $service)
                ->with('success', 'Service updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update service: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        try {
            // Check if service is used in any shipments
            // TODO: Implement when ShipmentService model exists
            $usageCount = 0; // $service->shipmentServices()->count();

            if ($usageCount > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete this service as it is being used in shipments.');
            }

            $service->delete();

            return redirect()->route('logistics.services.index')
                ->with('success', 'Service deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete service: ' . $e->getMessage());
        }
    }

    /**
     * Get services by criteria for AJAX requests
     */
    public function getByCriteria(Request $request)
    {
        $search = $request->get('search', '');
        $category = $request->get('category');
        $billingType = $request->get('billing_type');
        $provider = $request->get('provider');
        $mandatoryOnly = $request->get('mandatory_only', false);
        $cargoType = $request->get('cargo_type');

        $query = Service::where('status', 'Active');

        if ($category) {
            $query->where('service_category', $category);
        }

        if ($billingType) {
            $query->where('billing_type', $billingType);
        }

        if ($provider) {
            $query->where('service_provider', $provider);
        }

        if ($mandatoryOnly) {
            $query->where('is_mandatory', true);
        }

        if ($cargoType) {
            $query->forCargoType($cargoType);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('service_name', 'like', "%{$search}%")
                    ->orWhere('service_code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $services = $query->select('id', 'service_code', 'service_name', 'service_category', 'billing_type', 'base_rate', 'rate_currency')
            ->orderBy('service_name')
            ->limit(20)
            ->get();

        return response()->json($services);
    }

    /**
     * Toggle service status
     */
    public function toggleStatus(Service $service)
    {
        $newStatus = $service->status === 'Active' ? 'Inactive' : 'Active';

        $service->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Service status changed to {$newStatus}!");
    }

    /**
     * Get service statistics
     */
    public function getStatistics(Service $service)
    {
        $statistics = $service->getStatistics();

        return response()->json($statistics);
    }

    /**
     * Get services by category
     */
    public function getByCategory(Request $request)
    {
        $category = $request->get('category');

        if (!$category) {
            return response()->json(['error' => 'Service category required'], 400);
        }

        $services = Service::active()
            ->where('service_category', $category)
            ->select('id', 'service_code', 'service_name', 'billing_type', 'base_rate', 'rate_currency')
            ->orderBy('service_name')
            ->get();

        return response()->json($services);
    }

    /**
     * Get mandatory services
     */
    public function getMandatoryServices(Request $request)
    {
        $cargoType = $request->get('cargo_type');

        $query = Service::active()->where('is_mandatory', true);

        if ($cargoType) {
            $query->forCargoType($cargoType);
        }

        $services = $query->select('id', 'service_code', 'service_name', 'service_category', 'base_rate', 'rate_currency')
            ->orderBy('service_name')
            ->get();

        return response()->json($services);
    }

    /**
     * Calculate service rate
     */
    public function calculateRate(Service $service, Request $request)
    {
        $quantity = $request->get('quantity', 1);
        $shipmentValue = $request->get('shipment_value', 0);
        $additionalParams = $request->get('additional_params', []);

        $baseRate = $service->calculateRate($quantity, $shipmentValue, $additionalParams);
        $taxAmount = $service->calculateTax($baseRate);
        $totalRate = $baseRate + $taxAmount;

        return response()->json([
            'base_rate' => $baseRate,
            'tax_amount' => $taxAmount,
            'total_rate' => $totalRate,
            'currency' => $service->rate_currency,
            'billing_details' => [
                'billing_type' => $service->billing_type,
                'rate_unit' => $service->rate_unit,
                'quantity' => $quantity,
                'tax_type' => $service->tax_type,
                'tax_percentage' => $service->tax_percentage
            ]
        ]);
    }

    /**
     * Get service usage report
     */
    public function getUsageReport(Service $service, Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth());
        $endDate = $request->get('end_date', now());

        $usageByMonth = $service->getUsageByMonth($startDate, $endDate);
        $revenueByMonth = $service->getRevenueByMonth($startDate, $endDate);
        $topClients = $service->getTopClients(10);

        return response()->json([
            'service_info' => [
                'service_name' => $service->service_name,
                'service_code' => $service->service_code,
                'service_category' => $service->service_category
            ],
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'usage_by_month' => $usageByMonth,
            'revenue_by_month' => $revenueByMonth,
            'top_clients' => $topClients,
            'statistics' => $service->getStatistics()
        ]);
    }

    /**
     * Get services for specific cargo type
     */
    public function getForCargoType(Request $request)
    {
        $cargoType = $request->get('cargo_type');

        if (!$cargoType) {
            return response()->json(['error' => 'Cargo type required'], 400);
        }

        $services = Service::active()
            ->forCargoType($cargoType)
            ->select('id', 'service_code', 'service_name', 'service_category', 'is_mandatory', 'base_rate', 'rate_currency')
            ->orderBy('is_mandatory', 'desc')
            ->orderBy('service_name')
            ->get();

        return response()->json($services);
    }

    /**
     * Check service availability
     */
    public function checkAvailability(Service $service, Request $request)
    {
        $date = $request->get('date', now());
        $cargoType = $request->get('cargo_type');

        $isEffective = $service->isEffectiveOn($date);
        $isAvailableForCargo = $cargoType ? $service->isAvailableForCargo($cargoType) : true;

        $availability = [
            'available' => $isEffective && $isAvailableForCargo,
            'effective_date_valid' => $isEffective,
            'cargo_type_valid' => $isAvailableForCargo,
            'service_status' => $service->status,
            'effective_from' => $service->effective_from,
            'effective_to' => $service->effective_to,
            'applicable_cargo_types' => $service->applicable_cargo_types,
            'estimated_duration' => $service->estimated_duration_display,
            'requires_approval' => $service->requires_approval
        ];

        return response()->json($availability);
    }

    /**
     * Get services with specific documents required
     */
    public function getByRequiredDocuments(Request $request)
    {
        $requiredDocuments = $request->get('documents', []);

        if (empty($requiredDocuments)) {
            return response()->json(['error' => 'Required documents list needed'], 400);
        }

        $services = Service::active()
            ->where(function ($query) use ($requiredDocuments) {
                foreach ($requiredDocuments as $document) {
                    $query->orWhereJsonContains('required_documents', $document);
                }
            })
            ->select('id', 'service_code', 'service_name', 'service_category', 'required_documents')
            ->orderBy('service_name')
            ->get();

        return response()->json($services);
    }

    /**
     * Generate service performance report
     */
    public function generatePerformanceReport(Service $service, Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth());
        $endDate = $request->get('end_date', now());

        $report = [
            'service_info' => [
                'service_name' => $service->service_name,
                'service_code' => $service->service_code,
                'service_category' => $service->service_category,
                'billing_type' => $service->billing_type
            ],
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'performance_metrics' => [
                'total_usage' => $service->total_usage,
                'period_usage' => $service->shipmentServices()
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
                'total_revenue' => $service->getTotalRevenue(),
                'period_revenue' => $service->shipmentServices()
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('charged_amount'),
                'average_rate' => $service->getAverageRate(),
                'performance_rating' => $service->getPerformanceRating()
            ],
            'usage_trends' => $service->getUsageByMonth($startDate, $endDate),
            'revenue_trends' => $service->getRevenueByMonth($startDate, $endDate),
            'top_clients' => $service->getTopClients(10)
        ];

        return response()->json($report);
    }
}
