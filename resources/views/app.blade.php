<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/crudgen/libs/alertifyjs/css/alertify.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/crudgen/libs/alertifyjs/css/themes/default.min.css') }}"/>
    <link href="{{ asset('vendor/crudgen/css/app.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script src="{{ asset('vendor/crudgen/js/app.js') }}"></script>
    @stack("style")
</head>
<body>

<main>
    @yield('content')
</main>

<script src="{{ asset('vendor/crudgen/libs/alertifyjs/alertify.min.js') }}"></script>

@stack("script")


</body>
</html>
