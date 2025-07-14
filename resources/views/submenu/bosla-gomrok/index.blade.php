@extends('layouts.app')

@section('title', 'Bosla From Gomrok')
@section('page_title', 'Bosla From Gomrok Management')
@section('breadcrumb', 'Home > Submenu > Bosla From Gomrok')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">📋 Bosla From Gomrok</h2>
            <p style="color: #64748b;">Manage customs box/paperwork documentation</p>
        </div>
        <a href="{{ route('submenu.bosla-gomrok.create') }}" class="btn btn-primary">+ Add New Bosla</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}" placeholder="Bosla name, code..." />
            <x-form-select name="customs_office" label="Customs Office" :options="$customsOffices->pluck('name', 'name')"
                value="{{ request('customs_office') }}" />
            <x-form-select name="document_type" label="Document Type" :options="[
                'Export' => 'Export Declaration',
                'Import' => 'Import Declaration',
                'Transit' => 'Transit Declaration',
                'Temporary' => 'Temporary Admission',
            ]"
                value="{{ request('document_type') }}" />
            <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ request('status') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('submenu.bosla-gomrok.index') }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Bosla Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Bosla Code</th>
                    <th>Bosla Name</th>
                    <th>Document Type</th>
                    <th>Customs Office</th>
                    <th>Processing Time</th>
                    <th>Cost (USD)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($boslaItems as $bosla)
                    <tr>
                        <td><strong>{{ $bosla->code }}</strong></td>
                        <td>{{ $bosla->name }}</td>
                        <td>{{ $bosla->document_type }}</td>
                        <td>{{ $bosla->customs_office }}</td>
                        <td>{{ $bosla->processing_hours }} hours</td>
                        <td>${{ number_format($bosla->cost, 2) }}</td>
                        <td><span class="status-badge status-{{ strtolower($bosla->status) }}">{{ $bosla->status }}</span>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('submenu.bosla-gomrok.show', $bosla) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('submenu.bosla-gomrok.edit', $bosla) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('submenu.bosla-gomrok.destroy', $bosla) }}" method="POST"
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
                            No bosla items found. <a href="{{ route('submenu.bosla-gomrok.create') }}"
                                style="color: var(--primary-color);">Create your first bosla</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($boslaItems->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $boslaItems->links() }}
            </div>
        @endif
    </x-card>
@endsection
