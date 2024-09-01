@props([
    'activeClass' => 'font-medium !text-coral',
    'defaultClass' => 'hover:text-white outline-none focus:underline',
])

<nav class="py-2.5 top-0 sticky bg-dark-300 shadow-sm xl:px-0 px-6 z-30">
    <div class="flex justify-between max-w-screen-lg mx-auto items-center">
        <a href="{{ route('home') }}" class="outline-none">
            <img src="{{ asset('favicon.svg') }}" alt="Sertxu Dev" class="h-10">
        </a>

        <div class="space-x-6 hidden md:block text-sm text-neutral-300">
            <a href="{{ route('home') }}" @class([$defaultClass, $activeClass => request()->routeIs('home')])>Home</a>
            <a href="{{ route('posts.index') }}" @class([$defaultClass, $activeClass => request()->routeIs('posts.*')])>Blog</a>
            <a href="{{ route('projects.index') }}" @class([$defaultClass, $activeClass => request()->routeIs('projects.*')])>Projects</a>
            <a href="/uses" @class([$defaultClass, $activeClass => request()->routeIs('uses')])>Uses</a>
            <a href="{{ route('education.index') }}" @class([$defaultClass, $activeClass => request()->routeIs('education.*')])>Education</a>
            <a href="{{ route('experience.index') }}" @class([$defaultClass, $activeClass => request()->routeIs('experience.*')])>Experience</a>
        </div>
    </div>
</nav>
