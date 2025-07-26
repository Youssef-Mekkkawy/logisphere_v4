{{-- Replace resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Profile Settings - LogiFlow')
@section('page_title', 'Profile Settings')

@section('content')
    <div style="display: grid; grid-template-columns: 1fr; gap: 30px; max-width: 800px;">

        {{-- Update Profile Information --}}
        <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <h3 style="color: #1e40af; margin-bottom: 20px;">Update Profile Information</h3>

            <form method="POST" action="{{ route('user.profile.update') }}">
                @csrf
                @method('patch')

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-input"
                            value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input"
                            value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    @if (session('status') === 'profile-updated')
                        <span style="color: #059669; padding: 8px 16px;">Profile updated successfully!</span>
                    @endif
                </div>
            </form>
        </div>

        {{-- Update Password --}}
        <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <h3 style="color: #1e40af; margin-bottom: 20px;">Update Password</h3>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div style="display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-input" required>
                        @error('current_password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input" required>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                        @error('password_confirmation')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                    @if (session('status') === 'password-updated')
                        <span style="color: #059669; padding: 8px 16px;">Password updated successfully!</span>
                    @endif
                </div>
            </form>
        </div>

        {{-- Delete Account (Optional) --}}
        <div
            style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border-left: 4px solid #ef4444;">
            <h3 style="color: #ef4444; margin-bottom: 20px;">Delete Account</h3>
            <p style="color: #6b7280; margin-bottom: 20px;">
                Once your account is deleted, all of its resources and data will be permanently deleted.
                Please download any data or information that you wish to retain.
            </p>

            <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Account</button>

            {{-- Hidden delete form --}}
            <form id="delete-form" method="POST" action="{{ route('profile.destroy') }}" style="display: none;">
                @csrf
                @method('delete')
            </form>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
@endsection
