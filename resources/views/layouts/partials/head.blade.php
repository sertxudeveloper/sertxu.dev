<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}"/>

<meta name="author" content="sertxu.dev"/>
<meta name="robots" content="index, follow"/>

<meta name="applicable-device" content="pc, mobile"/>
<link rel="canonical" content="{{ url()->current() }}"/>

<meta name="keywords" content="sertxu.dev, sertxudev, sertxudeveloper, Sergio Peris, tutorial, personal, portfolio, social, cv"/>

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

    <!-- Open Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->title }}"/>
    <meta property="og:description" content="{{ $post->excerpt }}"/>
    <meta property="og:image" content="{{ $post->getFirstMediaUrl('thumbnail') }}"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:site_name" content="sertxu.dev"/>

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $post->excerpt }}">
    <meta property="og:image" content="{{ $post->getFirstMediaUrl('thumbnail') }}">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="sertxu.dev">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $post->title }}">
    <meta name="twitter:description" content="{{ $post->excerpt }}">
    <meta name="twitter:image" content="{{ $post->getFirstMediaUrl('thumbnail') }}">

@elseif(request()->routeIs('projects.show'))
    <title>{{ $project->title }} | {{ config('app.name') }}</title>
    <meta name="description" content="{{ $project->description }}"/>

    <!-- Open Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $project->title }}"/>
    <meta property="og:description" content="{{ $project->description }}"/>
    <meta property="og:image" content="{{ $project->getFirstMediaUrl('thumbnail') }}"/>
    <meta property="og:url" content="{{ url()->current() }}"/>

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $project->title }}">
    <meta property="og:description" content="{{ $project->description }}">
    <meta property="og:image" content="{{ $project->getFirstMediaUrl('thumbnail') }}">

@else
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="Hi, I'm Sertxu a full-stack developer, open-source maintainer, and content creator."/>

    <!-- Open Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Sergio Peris' blog and projects. Let's learn new things!"/>
    <meta property="og:description" content="Hi, I'm Sertxu a full-stack developer, open-source maintainer, and content creator."/>
    <meta property="og:image" content="{{ asset('social.png') }}"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:site_name" content="sertxu.dev"/>

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Sergio Peris' blog and projects. Let's learn new things!">
    <meta property="og:description" content="Hi, I'm Sertxu a full-stack developer, open-source maintainer, and content creator.">
    <meta property="og:image" content="{{ asset('social.png') }}">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="sertxu.dev">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Sergio Peris' blog and projects. Let's learn new things!">
    <meta name="twitter:description" content="Hi, I'm Sertxu a full-stack developer, open-source maintainer, and content creator.">
    <meta name="twitter:image" content="{{ asset('social.png') }}">
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

@yield('head')
