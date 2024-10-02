<nav x-data="{ open: false }" class="py-2.5 top-0 sticky bg-dark-300 shadow-sm xl:px-0 px-6 z-30">
    <div class="flex justify-between max-w-screen-lg mx-auto items-center">
        <a href="{{ route('home') }}" class="outline-none">
            @persist('logo')
                <img src="{{ asset('favicon.svg') }}" alt="Sertxu Dev" class="h-10">
            @endpersist
        </a>

        <!-- Desktop menu -->
        <div class="hidden md:block text-sm text-neutral-300">
            {{ $slot }}
        </div>

        <!-- Mobile menu opener -->
        <div class="flex inset-y-0 items-center right-0 md:hidden absolute pr-3">
            <button
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 outline-none bg-transparent ml-3"
                aria-label="Main menu" aria-expanded="false" @click="open = !open">

                <!-- Icon menu closed -->
                <div x-show="!open"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="transform opacity-0"
                    x-transition:enter-end="transform opacity-100">
                    <x-icons.bars />
                </div>

                <!-- Icon menu open -->
                <div x-show="open"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="transform opacity-0"
                    x-transition:enter-end="transform opacity-100">
                    <x-icons.times />
                </div>
            </button>
        </div>
        
        <!-- Mobile menu -->
        <div x-show="open"
            class="absolute bg-dark-300 md:hidden transform w-screen z-10"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="transform opacity-0 -translate-y-1/2"
            x-transition:enter-end="transform opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="transform opacity-100 translate-y-0"
            x-transition:leave-end="transform opacity-0 -translate-y-1/2">

            <div class="px-2 pt-2 pb-3">
                {{ $slot }}
            </div>
        </div>
    </div>
</nav>
