<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body>
<div class="grain-overlay"></div>

@include('layouts.partials.navbar')

{{ $slot }}

@persist('footer')
    <x-footer />
@endpersist

@livewireScriptConfig
@vite('resources/js/app.js')
</body>
</html>
