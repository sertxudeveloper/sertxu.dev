<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-pt-20">
<head>
    @include('layouts.partials.head')
</head>
<body class="font-sans antialiased bg-dark-100">

<x-navbar.index>
    <x-navbar.item :href="route('home')" :is-active="request()->routeIs('home')">Home</x-navbar.item>
    <x-navbar.item :href="route('posts.index')" :is-active="request()->routeIs('posts.*')">Blog</x-navbar.item>
    <x-navbar.item :href="route('projects.index')" :is-active="request()->routeIs('projects.*')">Projects</x-navbar.item>
    <x-navbar.item :href="route('uses')" :is-active="request()->routeIs('uses')">Uses</x-navbar.item>
    <x-navbar.item :href="route('education.index')" :is-active="request()->routeIs('education.*')">Education</x-navbar.item>
    <x-navbar.item :href="route('experience.index')" :is-active="request()->routeIs('experience.*')">Experience</x-navbar.item>
</x-navbar.index>

{{ $slot }}

@persist('footer')
    <x-footer />
@endpersist

@livewireScriptConfig
@vite('resources/js/app.js')
</body>
</html>
