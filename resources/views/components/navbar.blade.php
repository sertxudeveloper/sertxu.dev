@props([
    'activeClass' => 'font-medium !text-coral',
    'defaultClass' => 'hover:text-white'
])

<nav class="py-2.5 top-0 sticky bg-dark-300 shadow-sm xl:px-0 px-6 z-30">
    <div class="flex justify-between max-w-screen-lg mx-auto items-center">
        <div>
            <img src="{{ asset('favicon.svg') }}" alt="Sertxu Dev" class="h-10">
        </div>

        <div class="space-x-6 hidden md:block text-gray-300">
            <a href="/" @class([$defaultClass, $activeClass => request()->routeIs('home')])>About</a>
            <a href="/articles" @class([$defaultClass, $activeClass => request()->routeIs('blog.*')])>Articles</a>
            <a href="/projects" @class([$defaultClass, $activeClass => request()->routeIs('projects.*')])>Projects</a>
            <a href="/uses" @class([$defaultClass, $activeClass => request()->routeIs('uses')])>Uses</a>
        </div>
    </div>
</nav>
