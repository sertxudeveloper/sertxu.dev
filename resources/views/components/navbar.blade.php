<nav x-data="{ open: false }" class="top-0 sticky z-30">
    <div class="flex justify-between max-w-screen-lg mx-auto items-center relative py-2.5 px-6 bg-dark-300 shadow-sm xl:px-0 z-30">
        <a href="{{ route('home') }}" class="outline-none" wire:navigate>
            @persist('logo')
                <img src="{{ asset('favicon.svg') }}" alt="Sertxu Dev" class="h-10">
            @endpersist
        </a>

        <!-- Desktop menu -->
        <div class="hidden md:block text-sm text-neutral-300">
            {{ $slot }}
        </div>

        <!-- Mobile menu opener -->
        <div class="flex inset-y-0 items-center md:hidden">
            <button
                class="border border-neutral-800 p-2 rounded-md text-gray-500"
                aria-label="Main menu" aria-expanded="false" @click="open = !open">

                <!-- Icon menu closed -->
                <div x-show="!open"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="transform opacity-0"
                    x-transition:enter-end="transform opacity-100">
                    <x-icons.bars class="h-6" />
                </div>

                <!-- Icon menu open -->
                <div x-show="open"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="transform opacity-0"
                    x-transition:enter-end="transform opacity-100">
                    <x-icons.times class="h-6" />
                </div>
            </button>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open"
        class="absolute bg-dark-300 md:hidden transform w-full top-0 left-0 mt-[60px] border-t border-neutral-800"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="transform opacity-0 -translate-y-1/2"
        x-transition:enter-end="transform opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="transform opacity-100 translate-y-0"
        x-transition:leave-end="transform opacity-0 -translate-y-1/2">

        <div class="px-2 pt-2 pb-3 text-sm text-neutral-300 flex flex-col">
            {{ $slot }}
        </div>
    </div>
</nav>
