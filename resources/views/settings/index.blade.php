@extends('layouts.app')

@section('title', 'User Settings')
@section('page_title', 'User Settings')
@section('breadcrumb', 'Home > Settings')

@section('content')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
        <!-- User Preferences -->
        <x-card title="👤 User Preferences">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf

                <x-form-select name="language" label="Language" :options="[
                    'en' => 'English',
                    'ar' => 'العربية (Arabic)',
                    'fr' => 'Français (French)',
                    'es' => 'Español (Spanish)',
                ]"
                    value="{{ auth()->user()->getPreference('language', 'en') }}" />

                <x-form-select name="timezone" label="Timezone" :options="[
                    'UTC' => 'UTC',
                    'America/New_York' => 'Eastern Time (US)',
                    'America/Chicago' => 'Central Time (US)',
                    'America/Denver' => 'Mountain Time (US)',
                    'America/Los_Angeles' => 'Pacific Time (US)',
                    'Europe/London' => 'London (GMT)',
                    'Europe/Paris' => 'Paris (CET)',
                    'Asia/Dubai' => 'Dubai (GST)',
                    'Africa/Cairo' => 'Cairo (EET)',
                ]"
                    value="{{ auth()->user()->getPreference('timezone', 'UTC') }}" />

                <x-form-select name="date_format" label="Date Format" :options="[
                    'M d, Y' => date('M d, Y') . ' (Mar 15, 2024)',
                    'd/m/Y' => date('d/m/Y') . ' (15/03/2024)',
                    'm/d/Y' => date('m/d/Y') . ' (03/15/2024)',
                    'Y-m-d' => date('Y-m-d') . ' (2024-03-15)',
                ]"
                    value="{{ auth()->user()->getPreference('date_format', 'M d, Y') }}" />

                <x-form-select name="items_per_page" label="Items Per Page" :options="[
                    '10' => '10 items',
                    '25' => '25 items',
                    '50' => '50 items',
                    '100' => '100 items',
                ]"
                    value="{{ auth()->user()->getPreference('items_per_page', '25') }}" />

                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                </div>
            </form>
        </x-card>

        <!-- Notification Settings -->
        <x-card title="🔔 Notification Settings">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        <input type="checkbox" name="email_notifications" value="1"
                            {{ auth()->user()->getPreference('email_notifications', true) ? 'checked' : '' }}>
                        <span>📧 Email Notifications</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        <input type="checkbox" name="shipment_updates" value="1"
                            {{ auth()->user()->getPreference('shipment_updates', true) ? 'checked' : '' }}>
                        <span>📦 Shipment Status Updates</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        <input type="checkbox" name="overdue_alerts" value="1"
                            {{ auth()->user()->getPreference('overdue_alerts', true) ? 'checked' : '' }}>
                        <span>⚠️ Overdue Shipment Alerts</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        <input type="checkbox" name="daily_summary" value="1"
                            {{ auth()->user()->getPreference('daily_summary', false) ? 'checked' : '' }}>
                        <span>📊 Daily Summary Reports</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Save Notifications</button>
            </form>
        </x-card>
    </div>

    <!-- Security Settings -->
    <x-card title="🔐 Security Settings">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <!-- Change Password -->
            <div>
                <h4 style="margin-bottom: 1rem; color: #1e293b;">Password Security</h4>
                <p style="color: #64748b; margin-bottom: 1rem;">Last changed:
                    {{ auth()->user()->password_changed_at ? auth()->user()->password_changed_at->format('M d, Y') : 'Never' }}
                </p>
                <a href="{{ route('settings.change-password') }}" class="btn btn-warning">🔒 Change Password (Ctrl+G)</a>
            </div>

            <!-- Account Information -->
            <div>
                <h4 style="margin-bottom: 1rem; color: #1e293b;">Account Information</h4>
                <div style="color: #64748b; line-height: 1.6;">
                    <p><strong>Username:</strong> {{ auth()->user()->username }}</p>
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}</p>
                    <p><strong>Last Login:</strong>
                        {{ auth()->user()->last_login ? auth()->user()->last_login->format('M d, Y H:i') : 'Current session' }}
                    </p>
                </div>
            </div>

            <!-- Session Management -->
            <div>
                <h4 style="margin-bottom: 1rem; color: #1e293b;">Session Management</h4>
                <p style="color: #64748b; margin-bottom: 1rem;">Manage your active sessions and devices.</p>
                <button type="button" class="btn btn-secondary"
                    onclick="alert('Session management feature coming soon!')">View Active Sessions</button>
            </div>
        </div>
    </x-card>

    <!-- Export/Backup Settings -->
    <x-card title="💾 Data Management">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div>
                <h4 style="margin-bottom: 0.5rem; color: #1e293b;">Export Data</h4>
                <p style="color: #64748b; margin-bottom: 1rem; font-size: 0.875rem;">Export your personal data and activity.
                </p>
                <a href="{{ route('file.export') }}" class="btn btn-outline">📊 Export My Data</a>
            </div>

            <div>
                <h4 style="margin-bottom: 0.5rem; color: #1e293b;">Backup Settings</h4>
                <p style="color: #64748b; margin-bottom: 1rem; font-size: 0.875rem;">Backup your personal preferences.</p>
                <button type="button" class="btn btn-outline" onclick="alert('Backup feature coming soon!')">💾 Backup
                    Settings</button>
            </div>

            <div>
                <h4 style="margin-bottom: 0.5rem; color: #1e293b;">Reset Preferences</h4>
                <p style="color: #64748b; margin-bottom: 1rem; font-size: 0.875rem;">Reset all settings to defaults.</p>
                <button type="button" class="btn btn-danger"
                    onclick="if(confirm('Reset all preferences to default?')) alert('Reset functionality coming soon!')">🔄
                    Reset All</button>
            </div>
        </div>
    </x-card>
@endsection

@section('scripts')
    <script>
        // Auto-save preferences on change (optional enhancement)
        document.addEventListener('DOMContentLoaded', function() {
            const preferenceInputs = document.querySelectorAll(
                'select[name^="preference_"], input[name^="preference_"]');
            preferenceInputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Could implement auto-save functionality here
                    console.log('Preference changed:', this.name, this.value);
                });
            });
        });
    </script>
@endsection
