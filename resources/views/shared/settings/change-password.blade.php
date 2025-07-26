@extends('layouts.app')

@section('title', 'Change Password')
@section('page_title', 'Change Password (Ctrl+G)')
@section('breadcrumb', 'Home > Settings > Change Password')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <x-card title="🔒 Change Password">
            <form action="{{ route('settings.update-password') }}" method="POST">
                @csrf

                <x-form-input name="current_password" label="Current Password" type="password" required
                    placeholder="Enter your current password" />

                <x-form-input name="new_password" label="New Password" type="password" required
                    placeholder="Enter new password" />

                <x-form-input name="new_password_confirmation" label="Confirm New Password" type="password" required
                    placeholder="Confirm new password" />

                <!-- Password Requirements -->
                <div
                    style="margin-top: 1rem; padding: 1rem; background: #f8fafc; border-radius: 0.375rem; border-left: 4px solid var(--primary-color);">
                    <h4 style="margin-bottom: 0.5rem; color: #1e293b;">Password Requirements:</h4>
                    <ul style="font-size: 0.875rem; color: #64748b; margin-left: 1.5rem; line-height: 1.6;">
                        <li>At least 8 characters long</li>
                        <li>Contains at least one uppercase letter</li>
                        <li>Contains at least one lowercase letter</li>
                        <li>Contains at least one number</li>
                        <li>Contains at least one special character</li>
                    </ul>
                </div>

                <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">🔒 Update Password</button>
                    <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
@endsection

@section('scripts')
    <script>
        // Ctrl+G keyboard shortcut for change password
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'g') {
                e.preventDefault();
                // Already on change password page, focus on current password
                document.querySelector('input[name="current_password"]')?.focus();
            }
        });

        // Password strength indicator (optional enhancement)
        document.addEventListener('DOMContentLoaded', function() {
            const newPasswordInput = document.querySelector('input[name="new_password"]');
            if (newPasswordInput) {
                newPasswordInput.addEventListener('input', function() {
                    // Add password strength indicator logic here if needed
                });
            }
        });
    </script>
@endsection
