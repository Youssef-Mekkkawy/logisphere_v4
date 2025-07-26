@extends('layouts.app')

@section('title', 'Consignee & Notify Parties')

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">👥 Consignee &
                    Notify Parties</h1>
                <p style="color: #64748b;">Manage delivery parties and notification contacts</p>
            </div>
            <a href="{{ route('logistics.consignee-notify.create') }}" class="btn btn-primary">+ Add New Party</a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3>🔍 Filters</h3>
        </div>
        <div class="card-body">
            <form method="GET"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div class="form-group">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-input" value="{{ request('search') }}"
                        placeholder="Party name, code, email...">
                </div>

                <div class="form-group">
                    <label class="form-label">Party Type</label>
                    <select name="party_type" class="form-input">
                        <option value="">All Types</option>
                        <option value="Consignee" {{ request('party_type') == 'Consignee' ? 'selected' : '' }}>Consignee
                        </option>
                        <option value="Notify Party" {{ request('party_type') == 'Notify Party' ? 'selected' : '' }}>Notify
                            Party</option>
                        <option value="Both" {{ request('party_type') == 'Both' ? 'selected' : '' }}>Both</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Country</label>
                    <select name="country_id" class="form-input">
                        <option value="">All Countries</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ request('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Credit Rating</label>
                    <select name="credit_rating" class="form-input">
                        <option value="">All Ratings</option>
                        <option value="A" {{ request('credit_rating') == 'A' ? 'selected' : '' }}>A - Excellent
                        </option>
                        <option value="B" {{ request('credit_rating') == 'B' ? 'selected' : '' }}>B - Good</option>
                        <option value="C" {{ request('credit_rating') == 'C' ? 'selected' : '' }}>C - Fair</option>
                        <option value="D" {{ request('credit_rating') == 'D' ? 'selected' : '' }}>D - Poor</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('logistics.consignee-notify.index') }}" class="btn btn-secondary"
                        style="margin-left: 0.5rem;">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Parties Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Party Code</th>
                        <th>Party Name</th>
                        <th>Type</th>
                        <th>Contact Person</th>
                        <th>Location</th>
                        <th>Credit Rating</th>
                        <th>Freight Forwarder</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parties as $party)
                        <tr>
                            <td><strong>{{ $party->party_code }}</strong></td>
                            <td>{{ $party->party_name }}</td>
                            <td>
                                <span
                                    class="party-badge party-{{ strtolower(str_replace(' ', '-', $party->party_type)) }}">
                                    {{ $party->party_type }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $party->contact_person }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $party->email }}</div>
                            </td>
                            <td>
                                <div>{{ $party->city }}, {{ $party->country->name ?? $party->country }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $party->phone }}</div>
                            </td>
                            <td>
                                @if ($party->credit_rating)
                                    <span class="credit-badge credit-{{ strtolower($party->credit_rating) }}">
                                        {{ $party->credit_rating }} - {{ $party->credit_status }}
                                    </span>
                                @else
                                    <span style="color: #9ca3af;">Not Rated</span>
                                @endif
                            </td>
                            <td>
                                @if ($party->is_freight_forwarder)
                                    <span class="status-badge status-success">Yes</span>
                                @else
                                    <span class="status-badge status-info">No</span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="status-badge status-{{ strtolower($party->status) }}">{{ $party->status }}</span>
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('logistics.consignee-notify.show', $party) }}" class="btn btn-outline"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                <a href="{{ route('logistics.consignee-notify.edit', $party) }}" class="btn btn-success"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                @if (auth()->user()->isAdmin())
                                    <form action="{{ route('logistics.consignee-notify.destroy', $party) }}" method="POST"
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
                            <td colspan="9" style="text-align: center; padding: 2rem; color: #64748b;">
                                No consignee/notify parties found. <a href="{{ route('logistics.consignee-notify.create') }}"
                                    style="color: var(--primary-color);">Create your first party</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if (isset($parties) && $parties->hasPages())
                <div style="margin-top: 1.5rem; padding: 0 1.5rem;">
                    {{ $parties->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .party-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .party-consignee {
            background: #dbeafe;
            color: #1e40af;
        }

        .party-notify-party {
            background: #fef3c7;
            color: #92400e;
        }

        .party-both {
            background: #d1fae5;
            color: #065f46;
        }

        .credit-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .credit-a {
            background: #dcfce7;
            color: #166534;
        }

        .credit-b {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .credit-c {
            background: #fef3c7;
            color: #92400e;
        }

        .credit-d {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
        }

        .status-info {
            background: #dbeafe;
            color: #1d4ed8;
        }
    </style>
@endsection
