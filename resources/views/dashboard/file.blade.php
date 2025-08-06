@extends('layouts.app')

@section('title', 'File Management - logisphere')
@section('page-title', 'File Management')

@section('content')
<div class="tabs">
    <div class="tab active" data-tab="export">Export</div>
    <div class="tab" data-tab="import">Import</div>
    <div class="tab" data-tab="backup">Backup</div>
</div>

<div id="export-tab" class="tab-content active">
    <h3>Export Data</h3>
    <form method="POST" action="{{ route('tools.file.export') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Data Type</label>
                <select name="data_type" class="form-input" required>
                    <option value="">Select Data Type</option>
                    <option value="Shipments">Shipments</option>
                    <option value="Companies">Companies</option>
                    <option value="Employees">Employees</option>
                    <option value="Accounting Records">Accounting Records</option>
                </select>
                @error('data_type')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Format</label>
                <select name="format" class="form-input" required>
                    <option value="">Select Format</option>
                    <option value="Excel (.xlsx)">Excel (.xlsx)</option>
                    <option value="CSV (.csv)">CSV (.csv)</option>
                    <option value="PDF Report">PDF Report</option>
                </select>
                @error('format')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-input">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Export Data</button>
    </form>
</div>

<div id="import-tab" class="tab-content">
    <h3>Import Data</h3>
    <form method="POST" action="{{ route('tools.file.import') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label class="form-label">Select File</label>
            <input type="file" name="file" class="form-input" accept=".xlsx,.csv" required>
            @error('file')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Data Type</label>
            <select name="data_type" class="form-input" required>
                <option value="">Select Data Type</option>
                <option value="Shipments">Shipments</option>
                <option value="Companies">Companies</option>
                <option value="Employees">Employees</option>
            </select>
            @error('data_type')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Import Data</button>
    </form>
</div>

<div id="backup-tab" class="tab-content">
    <h3>System Backup</h3>
    <p>Create a complete backup of all system data.</p>
    <button class="btn btn-success">Create Backup</button>
    <button class="btn btn-secondary">Restore Backup</button>
</div>
@endsection