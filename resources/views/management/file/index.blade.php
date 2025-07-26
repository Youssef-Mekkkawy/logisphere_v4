@extends('layouts.app')

@section('title', 'File Operations')
@section('page_title', 'File Management')
@section('breadcrumb', 'Home > File Operations')

@section('content')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <!-- Export Data -->
        <x-card title="📤 Export Data">
            <form action="{{ route('tools.file.export') }}" method="POST">
                @csrf
                <x-form-select name="export_type" label="Data Type" :options="[
                    'shipments' => 'Shipments',
                    'companies' => 'Companies',
                    'employees' => 'Employees',
                    'invoices' => 'Invoices',
                    'all' => 'All Data',
                ]" required />

                <x-form-select name="format" label="Export Format" :options="[
                    'xlsx' => 'Excel (.xlsx)',
                    'csv' => 'CSV (.csv)',
                    'pdf' => 'PDF (.pdf)',
                ]" value="xlsx" required />

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <x-form-input name="date_from" label="From Date" type="date" />
                    <x-form-input name="date_to" label="To Date" type="date" />
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Export Data</button>
            </form>
        </x-card>

        <!-- Import Data -->
        <x-card title="📥 Import Data">
            <form action="{{ route('tools.file.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <x-form-select name="import_type" label="Data Type" :options="[
                    'shipments' => 'Shipments',
                    'companies' => 'Companies',
                    'employees' => 'Employees',
                    'ports' => 'Ports',
                ]" required />

                <div class="form-group">
                    <label class="form-label">Select File *</label>
                    <input type="file" name="import_file" class="form-input" accept=".xlsx,.csv" required>
                    <small style="color: #64748b;">Supported formats: Excel (.xlsx), CSV (.csv)</small>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="overwrite_existing" value="1">
                        Overwrite existing records
                    </label>
                </div>

                <button type="submit" class="btn btn-success" style="width: 100%;">Import Data</button>
            </form>
        </x-card>

        <!-- Backup & Restore -->
        <x-card title="💾 Backup & Restore">
            <div style="space-y: 1rem;">
                <form action="{{ route('tools.file.backup') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning" style="width: 100%;">Create Full Backup</button>
                </form>

                <form action="{{ route('tools.file.restore') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Backup File</label>
                        <input type="file" name="backup_file" class="form-input" accept=".zip,.sql">
                    </div>
                    <button type="submit" class="btn btn-danger" style="width: 100%;"
                        onclick="return confirm('This will overwrite all data. Continue?')">Restore Backup</button>
                </form>
            </div>
        </x-card>
    </div>

    <!-- Recent File Operations -->
    <x-card title="Recent File Operations">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Operation</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Records</th>
                    <th>Date</th>
                    <th>User</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fileOperations ?? [] as $operation)
                    <tr>
                        <td>{{ ucfirst($operation->operation) }}</td>
                        <td>{{ ucfirst($operation->type) }}</td>
                        <td><span
                                class="status-badge status-{{ $operation->status }}">{{ ucfirst($operation->status) }}</span>
                        </td>
                        <td>{{ number_format($operation->records_count) }}</td>
                        <td>{{ $operation->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ $operation->user->name }}</td>
                        <td>
                            @if ($operation->file_path)
                                {{-- {{ route('tools.file.download', $operation) }} --}}
                                <a href="" class="btn btn-outline"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Download</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #64748b; padding: 2rem;">
                            No file operations yet
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>
@endsection
