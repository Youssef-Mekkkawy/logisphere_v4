@extends('layouts.app')

@section('title', 'Services')
@section('page_title', 'Services Management')
@section('breadcrumb', 'Home > Submenu > Services')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">⚙️ Services Management</h2>
            <p style="color: #64748b;">Manage custom logistics services (Alt+S)</p>
        </div>
        <a href="{{ route('submenu.services.create') }}" class="btn btn-primary">+ Add New Service</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}" placeholder="Service name, code..." />
            <x-form-select name="service_category" label="Category" :options="[
                'Customs Clearance' => 'Customs Clearance',
                'Transportation' => 'Transportation',
                'Warehousing' => 'Warehousing',
                'Documentation' => 'Documentation',
                'Insurance' => 'Insurance',
                'Inspection' => 'Inspection',
                'Other' => 'Other',
            ]"
                value="{{ request('service_category') }}" />
            <x-form-select name="billing_type" label="Billing Type" :options="[
                'Fixed' => 'Fixed Rate',
                'Variable' => 'Variable Rate',
                'Percentage' => 'Percentage',
                'Hourly' => 'Hourly Rate',
            ]"
                value="{{ request('billing_type') }}" />
            <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ request('status') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('submenu.services.index') }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Services Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Service Code</th>
                    <th>Service Name</th>
                    <th>Category</th>
                    <th>Billing Type</th>
                    <th>Rate</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr>
                        <td><strong>{{ $service->code }}</strong></td>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->service_category }}</td>
                        <td>{{ $service->billing_type }}</td>
                        <td>
                            @if ($service->billing_type === 'Percentage')
                                {{ $service->rate }}%
                            @else
                                ${{ number_format($service->rate, 2) }}
                            @endif
                        </td>
                        <td>{{ $service->estimated_duration ?? 'N/A' }}</td>
                        <td><span
                                class="status-badge status-{{ strtolower($service->status) }}">{{ $service->status }}</span>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('submenu.services.show', $service) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('submenu.services.edit', $service) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('submenu.services.destroy', $service) }}" method="POST"
                                    style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: #64748b;">
                            No services found. <a href="{{ route('submenu.services.create') }}"
                                style="color: var(--primary-color);">Create your first service</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($services->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $services->links() }}
            </div>
        @endif
    </x-card>
@endsection

@section('scripts')
    <script>
        // Add Alt+S keyboard shortcut for services
        document.addEventListener('keydown', function(e) {
            if (e.altKey && e.key === 's') {
                e.preventDefault();
                window.location.href = "{{ route('submenu.services.index') }}";
            }
        });
    </script>
@endsection
