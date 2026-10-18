<x-app-layout
        title="Bio - sertxu.dev"
        description="All my links and socials on one place."
        :canonical="route('bio')"
        :breadcrumbs="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Bio', 'url' => route('bio')]
    ]"
>
    <section class="py-28 md:py-36 border-t border-neutral-900">
        <div>
            <img src="/sergio.webp" alt="Sergio Peris" width="480" height="480" fetchpriority="high" class="size-32 object-cover mx-auto shadow-lg rounded-full">
        </div>

        <div class="text-center mt-8">
            <h1 class="font-heading text-3xl md:text-4xl font-bold text-neutral-300">Sergio Peris</h1>
            <p class="text-neutral-400 text-base md:text-lg leading-relaxed mt-1">@sertxudev</p>
            <span class="bg-coral inline-block h-0.5 rounded-full w-12 mt-4"></span>
        </div>

        <div class="grid grid-cols-1 gap-4 max-w-md px-6 mx-auto mt-8">
            <a href="{{ route('goto', ['url' => 'https://sertxu.dev']) }}" target="_blank" rel="noopener noreferer" class="px-6 py-3 bg-ocean text-white rounded-lg font-medium flex items-center justify-center space-x-2">
                <x-icons.globe class="size-4"/> <span>My website</span>
            </a>

            <a href="{{ route('goto', ['url' => 'https://github.com/sertxudev']) }}" target="_blank" rel="noopener noreferer" class="px-6 py-3 bg-ocean text-white rounded-lg font-medium flex items-center justify-center space-x-2">
                <x-icons.github class="size-4"/> <span>GitHub (personal)</span>
            </a>

            <a href="{{ route('goto', ['url' => 'https://github.com/sertxudeveloper']) }}" target="_blank" rel="noopener noreferer" class="px-6 py-3 bg-ocean text-white rounded-lg font-medium flex items-center justify-center space-x-2">
                <x-icons.github class="size-4"/> <span>GitHub (org)</span>
            </a>

            <a href="{{ route('goto', ['url' => 'https://instagram.com/sertxudev']) }}" target="_blank" rel="noopener noreferer" class="px-6 py-3 bg-ocean text-white rounded-lg font-medium flex items-center justify-center space-x-2">
                <x-icons.instagram class="size-4"/> <span>Instagram</span>
            </a>

            <a href="{{ route('goto', ['url' => 'https://x.com/sertxudev']) }}" target="_blank" rel="noopener noreferer" class="px-6 py-3 bg-ocean text-white rounded-lg font-medium flex items-center justify-center space-x-2">
                <x-icons.twitter class="size-4"/> <span>X (aka. Twitter)</span>
            </a>

            <a href="{{ route('goto', ['url' => 'https://tiktok.com/@sertxudev']) }}" target="_blank" rel="noopener noreferer" class="px-6 py-3 bg-ocean text-white rounded-lg font-medium flex items-center justify-center space-x-2">
                <x-icons.tiktok class="size-4"/> <span>TikTok</span>
            </a>

            <a href="{{ route('goto', ['url' => 'https://linkedin.com/in/sertxudev']) }}" target="_blank" rel="noopener noreferer" class="px-6 py-3 bg-ocean text-white rounded-lg font-medium flex items-center justify-center space-x-2">
                <x-icons.linkedin class="size-4"/> <span>LinkedIn</span>
            </a>

            <a href="{{ route('goto', ['url' => 'https://threads.com/@sertxudev']) }}" target="_blank" rel="noopener noreferer" class="px-6 py-3 bg-ocean text-white rounded-lg font-medium flex items-center justify-center space-x-2">
                <x-icons.threads class="size-4"/> <span>Threads</span>
            </a>
        </div>
    </section>
</x-app-layout>
