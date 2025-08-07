<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;


// Import your models
use App\Models\{
    Invoice,
    Payment,
    EmployeeAdvance,
    JobAssignment,
    Expense,
    Account,
    Company,
    Shipment,
    User
};

// Import your policies
use App\Policies\{
    InvoicePolicy,
    PaymentPolicy,
    AdvancePolicy,
    JobPolicy,
    ExpensePolicy,
    AccountPolicy,
    CompanyPolicy,
    ShipmentPolicy,
    UserPolicy,
    Employee
};



namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Management\Shipment;
use App\Models\Management\Company;
use App\Models\Management\Employee;
use App\Models\Auth\User;
use App\Policies\ShipmentPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\UserPolicy;

class PolicyServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Shipment::class => ShipmentPolicy::class,
        Company::class => CompanyPolicy::class,
        // Employee::class => EmployeePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define additional gates
        Gate::define('manage-employees', function ($user) {
            return $user->hasAnyRole(['admin', 'manager']);
        });

        Gate::define('manage-accounting', function ($user) {
            return $user->hasAnyRole(['admin', 'manager']);
        });

        Gate::define('manage-settings', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('view-reports', function ($user) {
            return $user->hasAnyRole(['admin', 'manager']);
        });

        Gate::define('export-data', function ($user) {
            return $user->hasAnyRole(['admin', 'manager']);
        });
    }
}
