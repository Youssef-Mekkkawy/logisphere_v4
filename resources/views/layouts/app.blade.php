<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LogiFlow - Logistics Management System')</title>
    
    <!-- Use public directory CSS instead of Vite -->
    <link href="{{ asset('css/logiflow.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <div class="container">
        @include('components.sidebar')
        
        <main class="main-content">
            @include('components.header')
            
            <div class="content-area">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Use public directory JS instead of Vite -->
    <script src="{{ asset('js/logiflow.js') }}"></script>
    @stack('scripts')
</body>
</html>