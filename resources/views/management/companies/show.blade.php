@extends('layouts.app')

@section('title', $company->name . ' - LogiFlow')
@section('page-title', 'Company Details')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('management.companies.index') }}" class="btn btn-secondary">← Back to Companies</a>
    <a href="{{ route('management.companies.edit', $company) }}" class="btn btn-primary">Edit Company</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
    <!-- Main Company Info -->
    <div>
        <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
            <h3 style="color: #1e40af; margin-bottom: 20px;">{{ $company->name }}</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Company Type</label>
                    <span class="status-badge status-{{ strtolower($company->type) }}">{{ $company->type }}</span>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Status</label>
                    <span class="status-badge status-{{ strtolower($company->status) }}">{{ $company->status }}</span>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Contact Person</label>
                    <p style="margin: 0;">{{ $company->contact_person ?: 'N/A' }}</p>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Email</label>
                    <p style="margin: 0;">
                        @if($company->email)
                            <a href="mailto:{{ $company->email }}" style="color: #3b82f6;">{{ $company->email }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Phone</label>
                    <p style="margin: 0;">
                        @if($company->phone)
                            <a href="tel:{{ $company->phone }}" style="color: #3b82f6;">{{ $company->phone }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Country</label>
                    <p style="margin: 0;">{{ $company->country ?: 'N/A' }}</p>
                </div>
                
                @if($company->service_type)
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Service Type</label>
                    <p style="margin: 0;">{{ $company->service_type }}</p>
                </div>
                @endif
            </div>
            
            @if($company->address)
            <div style="margin-top: 20px;">
                <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Address</label>
                <p style="margin: 0; padding: 10px; background: #f8fafc; border-radius: 8px;">{{ $company->address }}</p>
            </div>
            @endif
        </div>
        
        <!-- Shipments List -->
        <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <h4 style="color: #1e40af; margin-bottom: 20px;">Related Shipments ({{ $company->shipments->count() }})</h4>
            
            @if($company->shipments->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Shipment ID</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Status</th>
                            <th>ETA</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($company->shipments->take(10) as $shipment)
                        <tr>
                            <td>{{ $shipment->shipment_id }}</td>
                            <td>{{ $shipment->originPort->name ?? 'N/A' }}</td>
                            <td>{{ $shipment->destinationPort->name ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipment->status)) }}">
                                    {{ $shipment->status }}
                                </span>
                            </td>
                            <td>{{ $shipment->eta ? $shipment->eta->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('management.shipments.show', $shipment) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if($company->shipments->count() > 10)
                    <p style="text-align: center; margin-top: 15px;">
                        <a href="{{ route('management.shipments.index', ['company' => $company->id]) }}" class="btn btn-primary">
                            View All {{ $company->shipments->count() }} Shipments
                        </a>
                    </p>
                @endif
            @else
                <p style="text-align: center; color: #6b7280; padding: 40px;">
                    No shipments found for this company.
                    <br><br>
                    <a href="{{ route('management.shipments.create', ['company_id' => $company->id]) }}" class="btn btn-primary">
                        Create First Shipment
                    </a>
                </p>
            @endif
        </div>
    </div>
    
    <!-- Sidebar Info -->
    <div>
        <!-- Quick Stats -->
        <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Quick Stats</h4>
            
            <div style="margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Total Shipments</span>
                    <strong style="color: #1e40af;">{{ $company->shipments->count() }}</strong>
                </div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Active Shipments</span>
                    <strong style="color: #059669;">{{ $company->shipments->whereNotIn('status', ['Delivered', 'Cancelled'])->count() }}</strong>
                </div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Delivered</span>
                    <strong style="color: #6b7280;">{{ $company->shipments->where('status', 'Delivered')->count() }}</strong>
                </div>
            </div>
        </div>
        
        <!-- Company Details -->
        <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Company Details</h4>
            
            <div style="margin-bottom: 10px;">
                <small style="color: #6b7280;">Created</small>
                <div>{{ $company->created_at->format('M d, Y') }}</div>
            </div>
            
            <div style="margin-bottom: 10px;">
                <small style="color: #6b7280;">Last Updated</small>
                <div>{{ $company->updated_at->format('M d, Y H:i') }}</div>
            </div>
            
            <div style="margin-bottom: 10px;">
                <small style="color: #6b7280;">Company ID</small>
                <div>#{{ $company->id }}</div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Quick Actions</h4>
            
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('management.companies.edit', $company) }}" class="btn btn-primary" style="text-align: center;">
                    Edit Company
                </a>
                
                <a href="{{ route('management.shipments.create', ['company_id' => $company->id]) }}" class="btn btn-secondary" style="text-align: center;">
                    Create Shipment
                </a>
                
                @if($company->email)
                <a href="mailto:{{ $company->email }}" class="btn btn-secondary" style="text-align: center;">
                    Send Email
                </a>
                @endif
                
                @if(auth()->user()->isAdmin())
                <form method="POST" action="{{ route('management.companies.destroy', $company) }}" style="margin-top: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="background: #dc2626; color: white; width: 100%;"
                            onclick="return confirm('Are you sure you want to delete this company? This will also affect related shipments.')">
                        Delete Company
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection