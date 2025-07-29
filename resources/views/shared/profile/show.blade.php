@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5>Profile Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ auth()->user()->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ auth()->user()->email }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="male" {{ auth()->user()->gender === 'male' ? 'selected' : '' }}>
                                            Male</option>
                                        <option value="female" {{ auth()->user()->gender === 'female' ? 'selected' : '' }}>
                                            Female</option>
                                        <option value="other" {{ auth()->user()->gender === 'other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Your Avatar</h5>
                    <x-avatar :user="auth()->user()" :size="120" class="mb-3" />
                    <p class="text-muted">Avatar generated based on your role and gender</p>

                    <div class="role-info">
                        <strong>Current Role:</strong>
                        @if (auth()->user()->isAdmin())
                            <span class="badge bg-warning text-dark">Administrator</span>
                        @else
                            <span class="badge bg-primary">{{ auth()->user()->roles->first()->name ?? 'User' }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
