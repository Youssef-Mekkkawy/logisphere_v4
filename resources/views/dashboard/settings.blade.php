@extends('layouts.app')

@section('title', 'Settings - LogiFlow')
@section('page-title', 'Settings')

@section('content')
<div class="tabs">
    <div class="tab active" data-tab="change-password">Change Password</div>
    <div class="tab" data-tab="user-settings">User Settings</div>
    @if(auth()->user()->isAdmin())
    <div class="tab" data-tab="system-settings">System Settings</div>
    @endif
</div>

<div id="change-password-tab" class="tab-content active">
    <h3>Change Password (Ctrl+G)</h3>
    <form method="POST" action="{{ route('settings.password') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Current Password *</label>
                <input type="password" name="current_password" class="form-input" required>
                @error('current_password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">New Password *</label>
                <input type="password" name="new_password" class="form-input" required>
                @error('new_password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirm New Password *</label>
                <input type="password" name="new_password_confirmation" class="form-input" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Password</button>
    </form>
</div>

<div id="user-settings-tab" class="tab-content">
    <h3>User Preferences</h3>
    <form method="POST" action="{{ route('settings.profile') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-input" 
                       value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-input" 
                       value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Language</label>
                <select name="language" class="form-input">
                    <option value="english">English</option>
                    <option value="arabic">Arabic</option>
                    <option value="french">French</option>
                    <option value="spanish">Spanish</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Time Zone</label>
                <select name="timezone" class="form-input">
                    <option value="GMT+2">GMT+2 (Cairo)</option>
                    <option value="GMT+0">GMT+0 (London)</option>
                    <option value="GMT-5">GMT-5 (New York)</option>
                    <option value="GMT+8">GMT+8 (Shanghai)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Date Format</label>
                <select name="date_format" class="form-input">
                    <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                    <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                    <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Currency</label>
                <select name="currency" class="form-input">
                    <option value="USD">USD ($)</option>
                    <option value="EGP">EGP (£E)</option>
                    <option value="EUR">EUR (€)</option>
                </select>
            </div>
        </div>
        
        <h4 style="margin: 30px 0 20px 0;">Notification Preferences</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
            <label class="checkbox-label">
                <input type="checkbox" name="email_notifications" value="1" checked>
                Email notifications
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="sms_notifications" value="1" checked>
                SMS notifications
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="dark_mode" value="1">
                Dark mode
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="auto_save" value="1" checked>
                Auto-save forms
            </label>
        </div>
        
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>

@if(auth()->user()->isAdmin())
<div id="system-settings-tab" class="tab-content">
    <h3>System Configuration</h3>
    <form method="POST" action="{{ route('settings.profile') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Company Name</label>
                <input type="text" name="company_name" class="form-input" 
                       value="LogiFlow Logistics">
            </div>
            <div class="form-group">
                <label class="form-label">Company Address</label>
                <input type="text" name="company_address" class="form-input" 
                       value="123 Port Street, Cairo, Egypt">
            </div>
            <div class="form-group">
                <label class="form-label">Default Currency</label>
                <select name="default_currency" class="form-input">
                    <option value="USD">USD</option>
                    <option value="EGP">EGP</option>
                    <option value="EUR">EUR</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Backup Frequency</label>
                <select name="backup_frequency" class="form-input">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save System Settings</button>
    </form>
</div>
@endif
@endsection