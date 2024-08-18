<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-pt-20">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <meta name="description" content="Laravel - The PHP Framework For Web Artisans">

    <!-- Favicons -->
    <link rel="icon" sizes="192x192" href="{{ asset('icon@192.png') }}">
    <link rel="icon" sizes="128x128" href="{{ asset('icon@128.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
    <link rel="icon" sizes="any" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="apple-touch-icon" sizes="76x76"   href="{{ asset('icon@76.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('icon@120.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icon@152.png') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('icon@167.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icon@180.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <!-- Styles -->
    @livewireStyles
    @vite('resources/css/app.css')
</head>
<body class="font-sans antialiased bg-dark-100">

<x-navbar />

@yield('content')

<x-footer />
@livewireScriptConfig
@vite('resources/js/app.js')
</body>
</html>
