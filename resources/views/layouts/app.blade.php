<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-pt-20">
<head>
    @include('layouts.components.head')
</head>
<body class="font-sans antialiased bg-dark-100">

<x-navbar />

{{ $slot }}

@persist('footer')
    <x-footer />
@endpersist

@livewireScriptConfig
@vite('resources/js/app.js')
</body>
</html>
