<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

// Import your models
use App\Models\{
    Invoice,
    Payment,
    EmployeeAdvance,
    JobAssignment,
    Expense,
    Account,
    Company,
    Shipment
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

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
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
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define Gates for role-based access
        Gate::define('manage-settings', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        // Existing gates...
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });


        // New Accounting Gates
        Gate::define('approve-expenses', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        Gate::define('delete-invoices', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('delete-payments', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('delete-advances', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        Gate::define('write-off-advances', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-accounts', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-accounting-settings', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('view-financial-reports', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        Gate::define('export-data', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        // Register Policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        Gate::define('view-reports', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });

    }
}
