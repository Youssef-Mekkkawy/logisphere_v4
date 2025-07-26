@extends('layouts.app')

@section('title', 'Companies - LogiFlow')
@section('page-title', 'Companies')

@section('content')
    <div class="tabs">
        <div class="tab active" data-tab="client-companies">Client Companies</div>
        <div class="tab" data-tab="supplier-companies">Supplier Companies</div>
        <div class="tab" data-tab="add-company">Add Company</div>
    </div>

    <div id="client-companies-tab" class="tab-content active">
        <input type="text" class="search-bar" placeholder="Search client companies...">
        <a href="{{ route('management.companies.create') }}" class="btn btn-primary">Add New Client</a>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Country</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies->where('type', 'Client') as $company)
                    <tr>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->contact_person }}</td>
                        <td>{{ $company->email ?: 'N/A' }}</td>
                        <td>{{ $company->phone ?: 'N/A' }}</td>
                        <td>{{ $company->country ?: 'N/A' }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($company->status) }}">
                                {{ $company->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('management.companies.edit', $company) }}" class="btn btn-secondary">Edit</a>
                            <a href="{{ route('management.companies.show', $company) }}" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px;">
                            <p style="color: #6b7280;">No client companies found. <a
                                    href="{{ route('management.companies.create') }}">Add your first client</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="supplier-companies-tab" class="tab-content">
        <input type="text" class="search-bar" placeholder="Search supplier companies...">
        <a href="{{ route('management.companies.create') }}" class="btn btn-primary">Add New Supplier</a>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Service Type</th>
                    <th>Country</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies->where('type', 'Supplier') as $company)
                    <tr>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->contact_person }}</td>
                        <td>{{ $company->email ?: 'N/A' }}</td>
                        <td>{{ $company->service_type ?: 'N/A' }}</td>
                        <td>{{ $company->country ?: 'N/A' }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($company->status) }}">
                                {{ $company->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('management.companies.edit', $company) }}" class="btn btn-secondary">Edit</a>
                            <a href="{{ route('management.companies.show', $company) }}" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">
                            <p style="color: #6b7280;">No supplier companies found. <a
                                    href="{{ route('management.companies.create') }}">Add your first supplier</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="add-company-tab" class="tab-content">
        <h3>Add New Company</h3>
        <form method="POST" action="{{ route('management.companies.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Company Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Company Type</label>
                    <select name="type" class="form-input">
                        <option value="Client" {{ old('type', 'Client') == 'Client' ? 'selected' : '' }}>Client</option>
                        <option value="Supplier" {{ old('type') == 'Supplier' ? 'selected' : '' }}>Supplier</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Person *</label>
                    <input type="text" name="contact_person" class="form-input" value="{{ old('contact_person') }}"
                        required>
                    @error('contact_person')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-input" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-input" value="{{ old('country') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-input" value="{{ old('address') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Service Type</label>
                    <select name="service_type" class="form-input">
                        <option value="">Select Service Type</option>
                        <option value="Shipping" {{ old('service_type') == 'Shipping' ? 'selected' : '' }}>Shipping
                        </option>
                        <option value="Customs Clearance"
                            {{ old('service_type') == 'Customs Clearance' ? 'selected' : '' }}>Customs Clearance</option>
                        <option value="Trucking" {{ old('service_type') == 'Trucking' ? 'selected' : '' }}>Trucking
                        </option>
                        <option value="Warehousing" {{ old('service_type') == 'Warehousing' ? 'selected' : '' }}>
                            Warehousing</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Company</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </form>
    </div>

    @if ($companies instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="margin-top: 20px;">
            {{ $companies->links() }}
        </div>
    @endif
@endsection
