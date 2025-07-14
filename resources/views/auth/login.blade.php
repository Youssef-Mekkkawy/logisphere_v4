@extends('layouts.guest')

@section('title', 'Login - LogiFlow')

@section('content')
<div class="login-container">
    <div class="login-box">
        <div class="login-header">
            <div class="login-logo">
                <h1>🚢 LogiFlow</h1>
                <p>Logistics Management System</p>
            </div>
        </div>
        
        <form method="POST" action="{{ route('login') }}" class="login-form" id="login-form">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-input" 
                       placeholder="Enter your username" value="{{ old('username') }}" required>
                @error('username')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-input" 
                       placeholder="Enter your password" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember">
                    <span class="checkmark"></span>
                    Remember me
                </label>
            </div>
            
            <button type="submit" class="btn-login">Sign In</button>
            
            <div class="login-links">
                <a href="#" class="forgot-password">Forgot Password?</a>
            </div>
        </form>
        
        <div class="login-footer">
            <div class="demo-credentials">
                <h4>Demo Credentials:</h4>
                <div class="credential-item">
                    <strong>Admin:</strong> admin / admin123
                </div>
                <div class="credential-item">
                    <strong>Manager:</strong> manager / manager123
                </div>
                <div class="credential-item">
                    <strong>User:</strong> user / user123
                </div>
            </div>
        </div>
    </div>
    
    <div class="login-info">
        <h2>Welcome to LogiFlow</h2>
        <p>Your comprehensive logistics management solution for managing shipments, employees, companies, and accounting processes.</p>
        
        <div class="feature-list">
            <div class="feature-item">
                <span class="feature-icon">📦</span>
                <div>
                    <h4>Shipment Management</h4>
                    <p>Track and manage all your shipments from origin to destination</p>
                </div>
            </div>
            
            <div class="feature-item">
                <span class="feature-icon">🏢</span>
                <div>
                    <h4>Company Management</h4>
                    <p>Manage client and supplier relationships efficiently</p>
                </div>
            </div>
            
            <div class="feature-item">
                <span class="feature-icon">👷</span>
                <div>
                    <h4>Employee & Accounting</h4>
                    <p>Handle HR processes and financial operations seamlessly</p>
                </div>
            </div>
            
            <div class="feature-item">
                <span class="feature-icon">📊</span>
                <div>
                    <h4>Analytics & Reports</h4>
                    <p>Generate insights and reports for better decision making</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection