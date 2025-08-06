{{-- File: resources/views/auth/blocked-account.blade.php - Dedicated blocked account page --}}

@extends('layouts.guest')

@section('title', 'Account Blocked - logisphere')

@section('content')
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f9fafb;">
        <div
            style="max-width: 500px; width: 100%; padding: 40px; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); text-align: center;">

            <!-- Blocked Icon -->
            <div style="font-size: 4rem; margin-bottom: 20px;">🚫</div>

            <!-- Title -->
            <h2 style="color: #dc2626; margin-bottom: 15px; font-size: 2rem;">Account Blocked</h2>

            <!-- Message -->
            <p style="color: #6b7280; margin-bottom: 30px; font-size: 1.1rem; line-height: 1.6;">
                Your account has been temporarily blocked by an administrator.
                Please contact your system administrator to resolve this issue.
            </p>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    Try Different Account
                </a>

                <button onclick="window.location.href='mailto:admin@{{ config('employee.email_domain', '{{ ENV('EMPLOYEE_EMAIL_DOMAIN') }}') }}'" class="btn btn-secondary">
                    Contact Administrator
                </button>
            </div>

            <!-- Help Text -->
            <div style="margin-top: 30px; padding: 20px; background: #f8fafc; border-radius: 10px;">
                <h4 style="color: #374151; margin-bottom: 10px;">Need Help?</h4>
                <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">
                    If you believe this is an error, please contact your administrator with your employee ID and account
                    details.
                </p>
            </div>
        </div>
    </div>
@endsection
