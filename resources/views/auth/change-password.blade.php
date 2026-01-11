{{-- File: resources/views/auth/change-password.blade.php --}}
@extends('layouts.app')

@section('title', 'Change Password - logistics')
@section('page-title', 'Change Password')

@section('content')
    <div style="max-width: 500px; margin: 0 auto;">

        <!-- Force Password Change Notice -->
        @if (auth()->user()->mustChangePassword())
            <div
                style="background: #fef3c7; border: 1px solid #fbbf24; color: #92400e; padding: 20px; border-radius: 12px; margin-bottom: 30px;">
                <h4 style="color: #92400e; margin-bottom: 10px;">🔒 Password Change Required</h4>
                <p style="margin: 0;">
                    Your administrator has required you to change your password before you can continue using the system.
                    Please create a new, secure password below.
                </p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}"
            style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            @csrf

            <div style="margin-bottom: 30px; text-align: center;">
                <h3 style="color: #1e40af; margin-bottom: 10px;">Change Your Password</h3>
                <p style="color: #6b7280; margin: 0;">
                    Enter your current password and choose a new secure password.
                </p>
            </div>

            <!-- Current Password -->
            <div class="form-group" style="margin-bottom: 25px;">
                <label class="form-label">Current Password *</label>
                <input type="password" name="current_password" class="form-input" required
                    placeholder="Enter your current password" autocomplete="current-password">
                @error('current_password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- New Password -->
            <div class="form-group" style="margin-bottom: 25px;">
                <label class="form-label">New Password *</label>
                <input type="password" name="password" id="password" class="form-input" required
                    placeholder="Enter your new password" autocomplete="new-password">
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <!-- Password Strength Indicator -->
                <div style="margin-top: 8px;">
                    <div id="password-strength"
                        style="height: 4px; background: #e5e7eb; border-radius: 2px; overflow: hidden;">
                        <div id="strength-bar"
                            style="height: 100%; width: 0%; transition: all 0.3s ease; background: #ef4444;"></div>
                    </div>
                    <small id="strength-text" style="color: #6b7280; font-size: 12px;">Password strength will be shown
                        here</small>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group" style="margin-bottom: 25px;">
                <label class="form-label">Confirm New Password *</label>
                <input type="password" name="password_confirmation" class="form-input" required
                    placeholder="Confirm your new password" autocomplete="new-password">
            </div>

            <!-- Password Requirements -->
            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                <h5 style="color: #374151; margin-bottom: 15px;">Password Requirements:</h5>
                <ul style="margin: 0; padding-left: 20px; color: #6b7280; font-size: 14px;">
                    <li>At least 8 characters long</li>
                    <li>Contains uppercase and lowercase letters</li>
                    <li>Contains at least one number</li>
                    <li>Contains at least one special character (!@#$%^&*)</li>
                    <li>Different from your current password</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div style="text-align: center;">
                <button type="submit" class="btn btn-primary" style="min-width: 200px;">
                    <i class="fas fa-key"></i> Change Password
                </button>

                @if (!auth()->user()->mustChangePassword())
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary" style="margin-left: 15px;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                @endif
            </div>

            @if (auth()->user()->mustChangePassword())
                <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                    <p style="color: #6b7280; margin-bottom: 10px;">
                        Need help? Contact your system administrator.
                    </p>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="background: #6b7280;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            @endif
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');

            // Calculate strength
            let strength = 0;
            let feedback = [];

            if (password.length >= 8) {
                strength += 20;
            } else {
                feedback.push('At least 8 characters');
            }

            if (/[a-z]/.test(password)) {
                strength += 20;
            } else {
                feedback.push('Lowercase letter');
            }

            if (/[A-Z]/.test(password)) {
                strength += 20;
            } else {
                feedback.push('Uppercase letter');
            }

            if (/[0-9]/.test(password)) {
                strength += 20;
            } else {
                feedback.push('Number');
            }

            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                strength += 20;
            } else {
                feedback.push('Special character');
            }

            // Update UI
            strengthBar.style.width = strength + '%';

            if (strength < 40) {
                strengthBar.style.background = '#ef4444';
                strengthText.textContent = 'Weak - Need: ' + feedback.join(', ');
                strengthText.style.color = '#ef4444';
            } else if (strength < 80) {
                strengthBar.style.background = '#f59e0b';
                strengthText.textContent = 'Fair - Need: ' + feedback.join(', ');
                strengthText.style.color = '#f59e0b';
            } else if (strength < 100) {
                strengthBar.style.background = '#3b82f6';
                strengthText.textContent = 'Good - Need: ' + feedback.join(', ');
                strengthText.style.color = '#3b82f6';
            } else {
                strengthBar.style.background = '#10b981';
                strengthText.textContent = 'Strong password!';
                strengthText.style.color = '#10b981';
            }
        });

        // Focus on first input
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="current_password"]').focus();
        });
    </script>
@endsection
