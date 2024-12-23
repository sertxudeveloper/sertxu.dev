<meta charset="utf-8">
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

@if (request()->routeIs('posts.show'))
    @php
        $post = request()->route('post');
    @endphp

    <title>{{ $post->title }} | {{ config('app.name') }}</title>
    <meta name="description" content="{{ $post->excerpt }}"/>
    <meta name="image" content="{{ $post->getFirstMediaUrl('thumbnail') }}"/>

    <!-- Open Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->title }} | {{ config('app.name') }}"/>
    <meta property="og:description" content="{{ $post->excerpt }}"/>
    <meta property="og:image" content="{{ $post->getFirstMediaUrl('thumbnail') }}"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:site_name" content="sertxu.dev"/>
    <meta property="og:locale" content="{{ app()->getLocale() }}" />
    <meta property="article:author" content="Sergio Peris">

    <!-- Twitter Meta Tags -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="sertxu.dev">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $post->title }} | {{ config('app.name') }}">
    <meta property="twitter:description" content="{{ $post->excerpt }}">
    <meta property="twitter:image" content="{{ $post->getFirstMediaUrl('thumbnail') }}">
    <meta property="twitter:creator" content="sertxudev">

@elseif(request()->routeIs('projects.show'))
    @php
        $project = request()->route('project');
    @endphp

    <title>{{ $project->title }} | {{ config('app.name') }}</title>
    <meta name="description" content="{{ $project->description }}"/>
    <meta name="image" content="{{ $project->getFirstMediaUrl('thumbnail') }}"/>

    <!-- Open Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $project->title }} | {{ config('app.name') }}"/>
    <meta property="og:description" content="{{ $project->excerpt }}"/>
    <meta property="og:image" content="{{ $project->getFirstMediaUrl('thumbnail') }}"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:locale" content="{{ app()->getLocale() }}" />
    <meta property="article:author" content="Sergio Peris">

    <!-- Twitter Meta Tags -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="sertxu.dev">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $project->title }} | {{ config('app.name') }}">
    <meta property="twitter:description" content="{{ $project->excerpt }}">
    <meta property="twitter:image" content="{{ $project->getFirstMediaUrl('thumbnail') }}">
    <meta property="twitter:creator" content="sertxudev">

@else
    <title>Sergio Peris - {{ config('app.name') }}</title>
    <meta name="description" content="Hi, I'm Sergio Peris, aka Sertxu, a full-stack developer, open-source maintainer, and content creator."/>
    <meta name="image" content="{{ asset('social.png') }}"/>

    <!-- Open Meta Tags -->
    <meta property="og:type" content="profile">
    <meta property="og:title" content="Sergio Peris' blog and projects. Let's learn new things!"/>
    <meta property="og:description" content="Hi, I'm Sergio Peris, aka Sertxu, a full-stack developer, open-source maintainer, and content creator."/>
    <meta property="og:image" content="{{ asset('social.png') }}"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:site_name" content="sertxu.dev"/>
    <meta property="og:locale" content="{{ app()->getLocale() }}" />
    <meta property="profile:first_name" content="Sergio">
    <meta property="profile:last_name" content="Peris">
    <meta property="profile:username" content="sertxudev">

    <!-- Twitter Meta Tags -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="sertxu.dev">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="Sergio Peris' blog and projects. Let's learn new things!">
    <meta property="twitter:description" content="Hi, I'm Sergio Peris, aka Sertxu, a full-stack developer, open-source maintainer, and content creator.">
    <meta property="twitter:image" content="{{ asset('social.png') }}">
    <meta property="twitter:creator" content="@sertxudev">
@endif

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">

<!-- Styles -->
@livewireStyles
@vite('resources/css/app.css')
<style>
    .bg-doodles {
        background-image: url("{{ asset('doodles.png') }}");
    }
</style>

<script defer data-domain="sertxu.dev" src="https://plausible.binarycomet.net/js/script.hash.outbound-links.js"></script>

@yield('head')
