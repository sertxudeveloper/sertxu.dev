<x-app-layout>
    <section id="blog" x-data class="py-28 md:py-36 border-t border-neutral-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center" x-reveal>
                <h2 class="font-heading text-3xl md:text-4xl font-bold text-neutral-300">Blog</h2>
                <span class="bg-coral inline-block h-0.5 rounded-full w-12 mt-4"></span>
                <p class="text-neutral-400 text-base mt-3">
                    Thoughts on development, infrastructure, and the tools I use.
                </p>
            </div>

            <div x-reveal>
                <livewire:posts />
            </div>
        </div>
    </section>
</x-app-layout>
