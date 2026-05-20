<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body class="bg-neutral-950 text-neutral-100 antialiased selection:bg-[#ff3047]/50 selection:text-neutral-50">
<div class="grain-overlay"></div>

@include('layouts.partials.navbar')

{{ $slot }}

@persist('footer')
    <x-footer />
@endpersist

@livewireScriptConfig

</body>
</html>
