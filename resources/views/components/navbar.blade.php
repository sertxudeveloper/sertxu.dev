@props([
    'activeClass' => 'font-bold !text-coral',
    'defaultClass' => 'px-3 py-3 hover:text-white outline-none focus:underline',
])

<nav class="py-2.5 top-0 sticky bg-dark-300 shadow-sm xl:px-0 px-6 z-30">
    <div class="flex justify-between max-w-screen-lg mx-auto items-center">
        <a href="{{ route('home') }}" class="outline-none">
            @persist('logo')
                <img src="{{ asset('favicon.svg') }}" alt="Sertxu Dev" class="h-10">
            @endpersist
        </a>

        <div class="hidden md:block text-sm text-neutral-300">
            <a href="{{ route('home') }}" wire:navigate @class([$defaultClass, $activeClass => request()->routeIs('home')])>Home</a>
            <a href="{{ route('posts.index') }}" wire:navigate @class([$defaultClass, $activeClass => request()->routeIs('posts.*')])>Blog</a>
            <a href="{{ route('projects.index') }}" wire:navigate @class([$defaultClass, $activeClass => request()->routeIs('projects.*')])>Projects</a>
            <a href="/uses" wire:navigate @class([$defaultClass, $activeClass => request()->routeIs('uses')])>Uses</a>
            <a href="{{ route('education.index') }}" wire:navigate @class([$defaultClass, $activeClass => request()->routeIs('education.*')])>Education</a>
            <a href="{{ route('experience.index') }}" wire:navigate @class([$defaultClass, $activeClass => request()->routeIs('experience.*')])>Experience</a>
        </div>
    </div>
</nav>
