@extends('layouts.app')

@section('title', 'Master Configuration')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Submenu - Master
            Configuration</h1>
        <p style="color: #64748b;">Manage all system configuration settings</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <!-- Shipment Information -->
        <div class="card">
            <div class="card-header">
                <h3>📦 Shipment Information</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('ports.index') }}" class="btn btn-outline"
                        style="width: 100%; justify-content: flex-start;">
                        🚢 Ports Management
                    </a>
                    <a href="{{ route('shipping-agencies.index') }}" class="btn btn-outline"
                        style="width: 100%; justify-content: flex-start;">
                        🏢 Shipping Agencies
                    </a>
                    <a href="{{ route('shipment-types.index') }}" class="btn btn-outline"
                        style="width: 100%; justify-content: flex-start;">
                        📋 Shipment Types
                    </a>
                </div>
            </div>
        </div>

        <!-- Customs Clearance -->
        <div class="card">
            <div class="card-header">
                <h3>📄 Customs Clearance</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('submenu.coo-types.index') ?? '' }}" class="btn btn-outline"
                        style="width: 100%; justify-content: flex-start;">
                        📜 COO Types
                    </a>
                    {{-- {{ route('submenu.inspection-types.index') ?? '' }} --}}
                    <a href="" class="btn btn-outline" style="width: 100%; justify-content: flex-start;">
                        🔍 Inspection Types
                    </a>
                </div>
            </div>
        </div>

        <!-- Trucking -->
        <div class="card">
            <div class="card-header">
                <h3>🚚 Trucking</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('submenu.bosla-gomrok.index') }}" class="btn btn-outline"
                        style="width: 100%; justify-content: flex-start;">
                        📋 Bosla From Gomrok
                    </a>
                    <a href="{{ route('submenu.destinations.index') }}" class="btn btn-outline"
                        style="width: 100%; justify-content: flex-start;">
                        📍 Destinations
                    </a>
                    <!-- Update this line: -->
                    <a href="{{ route('submenu.container-loading.index') }}" class="btn btn-outline"
                        style="width: 100%; justify-content: flex-start;">
                        📦 Container Loading Points
                    </a>
                    <!-- Update this line: -->
                    <a href="{{ route('submenu.consignee-notify.index') }}" class="btn btn-outline"
                        style="width: 100%; justify-content: flex-start;">
                        👥 Consignee & Notify Parties
                    </a>
                </div>
            </div>
        </div>

        <!-- Ocean Freight -->
        <div class="card">
            <div class="card-header">
                <h3>🚢 Ocean Freight</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    {{-- {{ route('submenu.quantity-types.index') ?? '' }} --}}
                    <a href="" class="btn btn-outline" style="width: 100%; justify-content: flex-start;">
                        📊 Quantity Types
                    </a>
                    {{-- {{ route('submenu.shippers.index') ?? '' }} --}}
                    <a href="" class="btn btn-outline" style="width: 100%; justify-content: flex-start;">
                        🏢 Shipper Management
                    </a>
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="card">
            <div class="card-header">
                <h3>🛠️ Services</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    {{-- {{ route('submenu.services.index') ?? '' }} --}}
                    <a href="" class="btn btn-outline" style="width: 100%; justify-content: flex-start;">
                        ⚙️ Service Management
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Summary -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <h3>Configuration Summary</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                        {{ $configStats['ports'] ?? 0 }}
                    </div>
                    <div style="font-size: 0.875rem; color: #64748b;">Ports</div>
                </div>
                <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                        {{ $configStats['shipment_types'] ?? 0 }}
                    </div>
                    <div style="font-size: 0.875rem; color: #64748b;">Shipment Types</div>
                </div>
                <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                        {{ $configStats['inspection_types'] ?? 0 }}
                    </div>
                    <div style="font-size: 0.875rem; color: #64748b;">Inspection Types</div>
                </div>
                <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                        {{ $configStats['destinations'] ?? 0 }}
                    </div>
                    <div style="font-size: 0.875rem; color: #64748b;">Destinations</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Card Styles */
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Button Outline Style */
        .btn.btn-outline {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .btn.btn-outline:hover {
            background: var(--primary-color, #3b82f6);
            color: white;
            border-color: var(--primary-color, #3b82f6);
            transform: translateY(-1px);
        }
    </style>
@endsection
