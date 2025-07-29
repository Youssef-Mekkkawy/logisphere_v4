<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\ShipmentService;
use App\Services\CompanyService;
use App\Services\EmployeeService;
use App\Services\DashboardService;
use App\Services\PDFService;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ShipmentService::class, fn($app) => new ShipmentService());
        $this->app->singleton(CompanyService::class, fn($app) => new CompanyService());
        $this->app->singleton(EmployeeService::class, fn($app) => new EmployeeService());
        $this->app->singleton(DashboardService::class, fn($app) => new DashboardService(
            $app->make(ShipmentService::class),
            $app->make(CompanyService::class),
            $app->make(EmployeeService::class)
        ));
        $this->app->singleton(PDFService::class, fn($app) => new PDFService());

        if (file_exists(app_path('Helpers/helpers.php'))) {
            require_once app_path('Helpers/helpers.php');
        }
    }

    public function boot(): void
    {

        Schema::defaultStringLength(191);
        $this->shareViewData();
        $this->configureApplication();
        $this->registerViewComposers();
        // Define Gates for permissions
        Gate::before(function (User $user, string $ability) {
            // Check if user has the permission
            if ($user->hasPermission($ability)) {
                return true;
            }

            // Check for admin override
            if ($user->hasRole('admin')) {
                return true;
            }

            return null; // Continue with other authorization checks
        });

        // Define specific gates
        Gate::define('manage-roles', function (User $user) {
            return $user->hasPermission('roles.view') || $user->hasRole('admin');
        });

        Gate::define('manage-permissions', function (User $user) {
            return $user->hasPermission('roles.manage-permissions') || $user->hasRole('admin');
        });

        Gate::define('manage-users', function (User $user) {
            return $user->hasPermission('users.view') || $user->hasRole('admin');
        });
    }

    private function shareViewData(): void
    {
        View::share('appName', config('app.name', 'LogiFlow Logistics'));
        View::share('appVersion', '1.0.0');

        View::composer('*', function ($view) {
            $user = Auth::user();
            if ($user instanceof User) {
                $view->with([
                    'currentUser' => $user,
                    'userRole' => $user->role ?? 'user',
                    'unreadNotifications' => 0
                ]);
            }
        });
    }

    private function configureApplication(): void
    {
        config([
            'app.date_format' => 'Y-m-d',
            'app.datetime_format' => 'Y-m-d H:i:s',
            'app.display_date_format' => 'M d, Y',
            'app.display_datetime_format' => 'M d, Y H:i'
        ]);

        config([
            'logistics default_currency' => 'USD',
            'logistics default_weight_unit' => 'kg',
            'logistics default_volume_unit' => 'm³',
            'logistics shipment_id_prefix' => 'LGF',
            'logistics company_code_length' => 7,
            'logistics employee_id_prefix' => 'EMP'
        ]);

        config([
            'company.name' => 'LogiFlow Logistics',
            'company.address' => 'Your Company Address',
            'company.city' => 'Your City',
            'company.country' => 'Egypt',
            'company.phone' => '+20-xxx-xxx-xxxx',
            'company.email' => 'info@logiflow.com',
            'company.website' => 'www.logiflow.com',
            'company.tax_number' => 'TAX123456789',
            'company.registration_number' => 'REG123456789'
        ]);
    }

    private function registerViewComposers(): void
    {
        View::composer('dashboard', function ($view) {
            $user = Auth::user();
            if ($user instanceof User) {
                $dashboardService = app(DashboardService::class);
                $view->with('dashboardData', $dashboardService->getDashboardData());
            }
        });

        View::composer('components.sidebar', function ($view) {
            $user = Auth::user();
            if ($user instanceof User) {
                $view->with([
                    'sidebarMenus' => $this->getSidebarMenus($user),
                    'userPermissions' => $this->getUserPermissions($user)
                ]);
            }
        });

        View::composer('components.header', function ($view) {
            $user = Auth::user();
            if ($user instanceof User) {
                $view->with([
                    'breadcrumbs' => $this->getBreadcrumbs(),
                    'quickActions' => $this->getQuickActions()
                ]);
            }
        });
    }

    private function getSidebarMenus(User $user): array
    {
        $menus = [
            [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '📊',
                'route' => 'dashboard',
                'permission' => null
            ],
            [
                'id' => 'shipments',
                'title' => 'Shipments',
                'icon' => '📦',
                'route' => 'management.shipments.index',
                'permission' => null,
                'logistics' => [
                    ['title' => 'All Shipments', 'route' => 'management.shipments.index'],
                    ['title' => 'Create Shipment', 'route' => 'management.shipments.create', 'shortcut' => 'Ctrl+F1'],
                ]
            ],
            [
                'id' => 'companies',
                'title' => 'Companies',
                'icon' => '🏢',
                'route' => 'management.companies.index',
                'permission' => null,
                'logistics' => [
                    ['title' => 'All Companies', 'route' => 'management.companies.index'],
                    ['title' => 'Clients', 'route' => 'management.companies.index', 'params' => ['type' => 'Client']],
                    ['title' => 'Suppliers', 'route' => 'management.companies.index', 'params' => ['type' => 'Supplier']],
                ]
            ],
            [
                'id' => 'employees',
                'title' => 'Employees',
                'icon' => '👷',
                'route' => 'management.employees.index',
                'permission' => 'manage_employees',
                'shortcut' => 'Ctrl+E'
            ],
            [
                'id' => 'users',
                'title' => 'Users',
                'icon' => '👥',
                'route' => 'users.index',
                'permission' => 'admin',
                'shortcut' => 'Ctrl+U'
            ],
            [
                'id' => 'accounting',
                'title' => 'Accounting',
                'icon' => '💰',
                'route' => 'accounting.index',
                'permission' => 'manage_accounting',
                'logistics' => [
                    ['title' => 'Employee Jobs', 'route' => 'accounting.employee-jobs'],
                    ['title' => 'Employee Covenant', 'route' => 'accounting.employee-covenant'],
                    ['title' => 'Advance Types', 'route' => 'accounting.advance-types'],
                    ['title' => 'Company Payments', 'route' => 'accounting.company-payments'],
                    ['title' => 'Reports', 'route' => 'accounting.reports'],
                ]
            ],
            [
                'id' => 'logistics',
                'title' => 'logistics',
                'icon' => '⚙️',
                'route' => 'logistics index',
                'permission' => 'manage_settings',
                'logistics' => [
                    ['title' => 'Ports', 'route' => 'logistics ports'],
                    ['title' => 'Shipping Agency', 'route' => 'logistics shipping-agency'],
                    ['title' => 'Shipment Types', 'route' => 'logistics shipment-types'],
                    ['title' => 'COO Types', 'route' => 'logistics coo-types'],
                    ['title' => 'Inspection Types', 'route' => 'logistics inspection-types'],
                ]
            ],
            [
                'id' => 'file',
                'title' => 'File',
                'icon' => '📁',
                'route' => 'file.index',
                'permission' => null
            ],
            [
                'id' => 'settings',
                'title' => 'Settings',
                'icon' => '🔧',
                'route' => 'settings.index',
                'permission' => null,
                'logistics' => [
                    ['title' => 'Change Password', 'route' => 'settings.change-password', 'shortcut' => 'Ctrl+G'],
                    ['title' => 'User Settings', 'route' => 'settings.user-settings'],
                ]
            ]
        ];

        return array_filter($menus, function ($menu) use ($user) {
            if (!isset($menu['permission'])) return true;

            return match ($menu['permission']) {
                'admin' => $user->isAdmin(),
                'manage_employees' => $user->hasAnyRole(['admin', 'manager']),
                'manage_accounting' => $user->hasAnyRole(['admin', 'manager']),
                'manage_settings' => $user->isAdmin(),
                default => true,
            };
        });
    }

    private function getUserPermissions(User $user): array
    {
        return [
            'is_admin' => $user->isAdmin(),
            'is_manager' => $user->isManager(),
            'can_manage_employees' => $user->hasAnyRole(['admin', 'manager']),
            'can_manage_accounting' => $user->hasAnyRole(['admin', 'manager']),
            'can_manage_settings' => $user->isAdmin(),
            'can_create_shipments' => true,
            'can_edit_shipments' => $user->hasAnyRole(['admin', 'manager']),
            'can_delete_shipments' => $user->isAdmin()
        ];
    }

    private function getBreadcrumbs(): array
    {
        $route = request()->route();
        if (!$route) return [];

        $routeName = $route->getName();
        $breadcrumbs = [['title' => 'Dashboard', 'route' => 'dashboard']];
        $routeParts = explode('.', $routeName);

        if (count($routeParts) > 1) {
            $section = $routeParts[0];
            $action = $routeParts[1] ?? 'index';

            $sectionTitles = [
                'shipments' => 'Shipments',
                'companies' => 'Companies',
                'employees' => 'Employees',
                'users' => 'Users',
                'accounting' => 'Accounting',
                'logistics' => 'Settings',
                'file' => 'File Management',
                'settings' => 'Settings'
            ];

            if (isset($sectionTitles[$section])) {
                $breadcrumbs[] = ['title' => $sectionTitles[$section], 'route' => $section . '.index'];

                $actionTitles = [
                    'create' => 'Create New',
                    'edit' => 'Edit',
                    'show' => 'View Details'
                ];

                if (isset($actionTitles[$action])) {
                    $breadcrumbs[] = ['title' => $actionTitles[$action]];
                }
            }
        }

        return $breadcrumbs;
    }


    private function getQuickActions(): array
    {
        return [
            [
                'title' => 'New Shipment',
                'route' => 'shipments.create',
                'icon' => '➕',
                'shortcut' => 'Ctrl+F1',
                'class' => 'btn-primary'
            ],
            [
                'title' => 'Search',
                'action' => 'toggleSearch',
                'icon' => '🔍',
                'shortcut' => 'Ctrl+/',
                'class' => 'btn-outline'
            ]
        ];
    }
}
