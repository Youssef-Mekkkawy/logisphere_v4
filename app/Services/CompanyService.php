<?php

namespace App\services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CompanyService
{
    /**
     * Create a new company with validation and setup
     */
    public function createCompany(array $data): \App\Models\Company
    {
        $data['company_code'] = $this->generateCompanyCode($data['type']);
        $data['status'] = 'Active';
        $data['created_by'] = Auth::id();

        $company = \App\Models\Company::create($data);

        // Log company creation
        Log::info("Company created", [
            'company_id' => $company->id,
            'company_code' => $company->company_code,
            'name' => $company->name,
            'type' => $company->type
        ]);

        return $company;
    }

    /**
     * Generate unique company code
     */
    private function generateCompanyCode(string $type): string
    {
        $prefix = $type === 'Client' ? 'CLT' : 'SUP';

        $lastCompany = \App\Models\Company::where('company_code', 'like', $prefix . '%')
            ->orderBy('company_code', 'desc')
            ->first();

        if ($lastCompany) {
            $lastNumber = intval(substr($lastCompany->company_code, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get company performance metrics
     */
    public function getCompanyMetrics(\App\Models\Company $company): array
    {
        $shipments = $company->shipments();

        return [
            'total_shipments' => $shipments->count(),
            'active_shipments' => $shipments->whereIn('status', ['Pending', 'In Transit', 'At Port'])->count(),
            'delivered_shipments' => $shipments->where('status', 'Delivered')->count(),
            'total_value' => $shipments->sum('value'),
            'avg_shipment_value' => $shipments->avg('value'),
            'last_shipment_date' => $shipments->orderBy('created_at', 'desc')->first()?->created_at,
            'on_time_delivery_rate' => $this->calculateCompanyOnTimeRate($company)
        ];
    }

    /**
     * Calculate company-specific on-time delivery rate
     */
    private function calculateCompanyOnTimeRate(\App\Models\Company $company): float
    {
        $deliveredShipments = $company->shipments()
            ->where('status', 'Delivered')
            ->count();

        if ($deliveredShipments === 0) {
            return 0;
        }

        $onTimeShipments = $company->shipments()
            ->where('status', 'Delivered')
            ->whereColumn('actual_delivery_date', '<=', 'eta')
            ->count();

        return round(($onTimeShipments / $deliveredShipments) * 100, 1);
    }
}
