@extends('layouts.app')

@section('title', 'Master Configuration')
@section('page_title', 'Submenu - Master Configuration')
@section('breadcrumb', 'Home > Submenu')

@section('content')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <!-- Shipment Information -->
        <x-card title="📦 Shipment Information">
            <div style="space-y: 0.75rem;">
                <a href="{{ route('submenu.ports.index') }}" class="btn btn-outline"
                    style="width: 100%; justify-content: flex-start;">
                    🚢 Ports Management
                </a>
                <a href="{{ route('submenu.shipping-agencies.index') }}" class="btn btn-outline"
                    style="width: 100%; justify-content: flex-start;">
                    🏢 Shipping Agencies
                </a>
                <a href="{{ route('submenu.shipment-types.index') }}" class="btn btn-outline"
                    style="width: 100%; justify-content: flex-start;">
                    📋 Shipment Types
                </a>
            </div>
        </x-card>

        <!-- Customs Clearance -->
        <x-card title="📄 Customs Clearance">
            <div style="space-y: 0.75rem;">
                <a href="{{ route('submenu.coo-types.index') }}" class="btn btn-outline"
                    style="width: 100%; justify-content: flex-start;">
                    📜 COO Types
                </a>
                <a href="{{ route('submenu.inspection-types.index') }}" class="btn btn-outline"
                    style="width: 100%; justify-content: flex-start;">
                    🔍 Inspection Types
                </a>
            </div>
        </x-card>

        <!-- Trucking -->
        <x-card title="🚚 Trucking">
            <div style="space-y: 0.75rem;">
                <a href="{{ route('submenu.bosla-gomrok.index') }}" class="btn btn-outline"
                    style="width: 100%; justify-content: flex-start;">
                    📋 Bosla From Gomrok
                </a>
                <a href="{{ route('submenu.destinations.index') }}" class="btn btn-outline"
                    style="width: 100%; justify-content: flex-start;">
                    📍 Destinations
                </a>
                <a href="{{ route('submenu.container-loading.index') }}" class="btn btn-outline"
                    style="width: 100%; justify-content: flex-start;">
                    📦 Container Loading Points
                </a>
                <a href="{{ route('submenu.consignee-notify.index') }}" class="btn btn-outline"
                    style="width: 100%; justify-content: flex-start;">
                    👥 Consignee & Notify Parties
                </a>
            </div>
        </x-card>

        <!-- Ocean Freight -->
        <x-card title="🚢 Ocean Freight">
            <div style="space-y: 0.75rem;">
                <a href="{{ route('submenu.quantity-types.index') }}" class="btn btn-outline"
                    style="width: 100%; justify-content: flex-start;">
                    📊 Quantity Types
                </a>
                <a href="{{ route('submenu.shippers.index') }}" class="btn btn-outline"
                    style="width: 100%; justify-content: flex-start;">
                    🏢 Shipper Management
                </a>
            </div>
        </x-card>

        <!-- Services -->
        <x-card title="🛠️ Services">
            <div style="space-y: 0.75rem;">
                <a href="{{ route('submenu.services.index') }}" class="btn btn-outline"
                    style="width: 100%; justify-content: flex-start;">
                    ⚙️ Service Management
                </a>
            </div>
        </x-card>
    </div>

    <!-- Configuration Summary -->
    <x-card title="Configuration Summary">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                    {{ $configStats['ports'] ?? 0 }}</div>
                <div style="font-size: 0.875rem; color: #64748b;">Ports</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                    {{ $configStats['shipment_types'] ?? 0 }}</div>
                <div style="font-size: 0.875rem; color: #64748b;">Shipment Types</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                    {{ $configStats['inspection_types'] ?? 0 }}</div>
                <div style="font-size: 0.875rem; color: #64748b;">Inspection Types</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                    {{ $configStats['destinations'] ?? 0 }}</div>
                <div style="font-size: 0.875rem; color: #64748b;">Destinations</div>
            </div>
        </div>
    </x-card>
@endsection
