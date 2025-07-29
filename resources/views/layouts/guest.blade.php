<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login - LogiFlow')</title>
    
    <!-- Use public directory CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @yield('content')
    
    <!-- Use public directory JS -->
    <script src="{{ asset('js/logiflow.js') }}"></script>
</body>
</html>