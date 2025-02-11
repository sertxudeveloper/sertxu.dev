<section class="bg-dark-200 flex md:space-x-24 items-center justify-center py-32 min-h-screen flex-col md:flex-row space-y-12 relative">
    <div class="top-0 left-0 absolute h-full w-full z-0 pointer-events-none bg-doodles"></div>
    <img class="rounded-2xl w-48 md:w-72 block opacity-0 animate-slide-fade-in [animation-delay:100ms]!" src="{{ asset('dp.webp') }}" alt="Sergio Peris">

    <div class="text-center space-y-6 px-4 md:px-12">
        <h1 class="text-4xl md:text-5xl font-bold text-neutral-200 opacity-0 animate-slide-fade-in [animation-delay:100ms]!">Hi, I'm Sertxu</h1>
        <p class="text-xl md:text-2xl sm:text-3xl text-neutral-300 opacity-0 animate-slide-fade-in [animation-delay:200ms]!">Full&#8209;stack&nbsp;developer, open&#8209;source&nbsp;maintainer,<br>and content&nbsp;creator.</p>

        <div class="flex space-x-4 justify-center pt-4 opacity-0 animate-slide-fade-in [animation-delay:300ms]!">
            <x-social-button href="https://x.com/sertxudev" alt="X (Twitter)">
                <x-icons.twitter />
            </x-social-button>

            <x-social-button href="https://github.com/sertxudev" alt="GitHub">
                <x-icons.github />
            </x-social-button>

            <x-social-button href="https://linkedin.com/in/sertxudev" alt="LinkedIn">
                <x-icons.linkedin />
            </x-social-button>

            <x-social-button href="mailto:hello@sertxu.dev" alt="Mail address">
                <x-icons.email />
            </x-social-button>
        </div>
    </div>
</section>
