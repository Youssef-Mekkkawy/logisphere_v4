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
            'logistics.default_currency' => 'USD',
            'logistics.default_weight_unit' => 'kg',
            'logistics.default_volume_unit' => 'm³',
            'logistics.shipment_id_prefix' => 'LGF',
            'logistics.company_code_length' => 7,
            'logistics.employee_id_prefix' => 'EMP'
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
                'route' => 'shipments.index',
                'permission' => null,
                'submenu' => [
                    ['title' => 'All Shipments', 'route' => 'shipments.index'],
                    ['title' => 'Create Shipment', 'route' => 'shipments.create', 'shortcut' => 'Ctrl+F1'],
                ]
            ],
            [
                'id' => 'companies',
                'title' => 'Companies',
                'icon' => '🏢',
                'route' => 'companies.index',
                'permission' => null,
                'submenu' => [
                    ['title' => 'All Companies', 'route' => 'companies.index'],
                    ['title' => 'Clients', 'route' => 'companies.index', 'params' => ['type' => 'Client']],
                    ['title' => 'Suppliers', 'route' => 'companies.index', 'params' => ['type' => 'Supplier']],
                ]
            ],
            [
                'id' => 'employees',
                'title' => 'Employees',
                'icon' => '👷',
                'route' => 'employees.index',
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
                'submenu' => [
                    ['title' => 'Employee Jobs', 'route' => 'accounting.employee-jobs'],
                    ['title' => 'Employee Covenant', 'route' => 'accounting.employee-covenant'],
                    ['title' => 'Advance Types', 'route' => 'accounting.advance-types'],
                    ['title' => 'Company Payments', 'route' => 'accounting.company-payments'],
                    ['title' => 'Reports', 'route' => 'accounting.reports'],
                ]
            ],
            [
                'id' => 'submenu',
                'title' => 'Submenu',
                'icon' => '⚙️',
                'route' => 'submenu.index',
                'permission' => 'manage_settings',
                'submenu' => [
                    ['title' => 'Ports', 'route' => 'submenu.ports'],
                    ['title' => 'Shipping Agency', 'route' => 'submenu.shipping-agency'],
                    ['title' => 'Shipment Types', 'route' => 'submenu.shipment-types'],
                    ['title' => 'COO Types', 'route' => 'submenu.coo-types'],
                    ['title' => 'Inspection Types', 'route' => 'submenu.inspection-types'],
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
                'submenu' => [
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
                'submenu' => 'Settings',
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
