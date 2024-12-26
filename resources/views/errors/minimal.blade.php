<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-pt-20">
<head><meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <meta name="author" content="Sergio Peris"/>
    <meta name="robots" content="index, follow"/>

    <meta name="applicable-device" content="pc, mobile"/>
    <link rel="canonical" href="{{ url()->current() }}"/>

    <meta name="keywords" content="sertxu.dev, sertxudev, sertxudeveloper, Sergio Peris, tutorial, personal, portfolio, social, cv, laravel, website, tailwind, alpinejs, livewire, filament, ubuntu, windows, blog, projects"/>

    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-title" content="sertxu.dev" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />

    <link rel="manifest" href="/manifest.json"/>
    <meta name="theme-color" content="#171717"/>

    <!-- Favicons -->
    <link rel="icon" sizes="192x192" href="{{ asset('icon@192.png') }}">
    <link rel="icon" sizes="128x128" href="{{ asset('icon@128.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
    <link rel="shortcut icon"  type="image/x-icon" href="{{ asset('favicon.svg') }}"/>
    <link rel="icon" sizes="any" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="apple-touch-icon" sizes="76x76"   href="{{ asset('icon@76.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('icon@120.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icon@152.png') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('icon@167.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icon@180.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preload" href="https://fonts.bunny.net/css?family=arya:400,700|inter:300,400,500,600,700" as="style" type="text/css">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=arya:400,700|inter:300,400,500,600,700">

    <!-- Styles -->
    @livewireStyles
    @vite('resources/css/app.css')
    <style>
        .bg-doodles {
            background-image: url("{{ asset('doodles.webp') }}");
        }
    </style>

    @yield('head')
</head>
<body class="font-sans antialiased bg-dark-100">

@include('layouts.partials.navbar')

@yield('content')

@persist('footer')
<x-footer />
@endpersist

@livewireScriptConfig
@vite('resources/js/app.js')
</body>
</html>
