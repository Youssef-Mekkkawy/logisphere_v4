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
    UserPolicy
};

class PolicyServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Accounting Policies
        Invoice::class => InvoicePolicy::class,
        Payment::class => PaymentPolicy::class,
        EmployeeAdvance::class => AdvancePolicy::class,
        JobAssignment::class => JobPolicy::class,
        Expense::class => ExpensePolicy::class,
        Account::class => AccountPolicy::class,

        // Core Business Policies
        Company::class => CompanyPolicy::class,
        Shipment::class => ShipmentPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Additional Gates can be defined here if needed
        // Gate::define('custom-permission', function ($user) {
        //     return $user->role === 'admin';
        // });
    }
}
