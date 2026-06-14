<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'Sergio Peris, aka Sertxu. Full-Stack Developer & SysAdmin' }}</title>
<meta name="description" content="{{ $description ?? 'Full-Stack Developer & SysAdmin building reliable systems from Xàtiva, Valencia.' }}" />
<meta name="theme-color" content="#0a0a0c" />

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">

<meta property="og:title" content="{{ $title ?? 'Sergio Peris, aka Sertxu. Full-Stack Developer & SysAdmin' }}" />
<meta property="og:description" content="{{ $description ?? 'Full-Stack Developer & SysAdmin building reliable systems from Xàtiva, Valencia.' }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ $canonical ?? url()->current() }}" />

@isset($ogImage)
    <meta property="og:image" content="{{ $ogImage }}" />
@endisset

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $title ?? 'Sergio Peris, aka Sertxu. Full-Stack Developer & SysAdmin' }}" />
<meta name="twitter:description" content="{{ $description ?? 'Full-Stack Developer & SysAdmin building reliable systems from Xàtiva, Valencia.' }}" />
@isset($ogImage)
    <meta name="twitter:image" content="{{ $ogImage }}" />
@endisset

@isset($canonical)
    <link rel="canonical" href="{{ $canonical }}" />
@endisset

@include('layouts.partials.schema')

@fonts
@vite(['resources/css/app.css', 'resources/js/app.js'])

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
