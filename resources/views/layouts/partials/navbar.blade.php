<x-navbar.index>
    <x-navbar.item :href="route('home')" :is-active="request()->routeIs('home')">Home</x-navbar.item>
    <x-navbar.item :href="route('posts.index')" :is-active="request()->routeIs('posts.*')">Blog</x-navbar.item>
    <x-navbar.item :href="route('projects.index')" :is-active="request()->routeIs('projects.*')">Projects</x-navbar.item>
    <x-navbar.item :href="route('uses')" :is-active="request()->routeIs('uses')">Uses</x-navbar.item>
    <x-navbar.item :href="route('education.index')" :is-active="request()->routeIs('education.*')">Education</x-navbar.item>
    <x-navbar.item :href="route('experience.index')" :is-active="request()->routeIs('experience.*')">Experience</x-navbar.item>
</x-navbar.index>
