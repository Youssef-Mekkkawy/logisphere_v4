@extends('layouts.app')

@section('title', '404 - Page Not Found')
@section('page_title', '404 - Page Not Found')

@section('content')
    <div style="display: flex; align-items: center; justify-content: center; min-height: 60vh; text-align: center;">
        <div>
            <div style="font-size: 6rem; margin-bottom: 1rem;">🚢</div>
            <h1 style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1rem; font-weight: 700;">404</h1>
            <h2 style="color: #374151; margin-bottom: 1rem; font-size: 1.5rem;">Page Not Found</h2>
            <p style="color: #6b7280; margin-bottom: 2rem; max-width: 400px;">
                The page you're looking for doesn't exist yet. This feature is coming soon as we continue building the
                logistics management system.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    🏠 Back to Dashboard
                </a>
                <button onclick="history.back()" class="btn btn-outline">
                    ← Go Back
                </button>
            </div>

            <div
                style="margin-top: 2rem; padding: 1rem; background: #f8fafc; border-radius: 0.5rem; color: #64748b; font-size: 0.875rem;">
                <strong>Under Development:</strong> This section will be available in the next phase of development.
            </div>
        </div>
    </div>
@endsection
