<x-app-layout
        title="Uses - sertxu.dev"
        description="Know more about my preferred technologies and software."
        :canonical="route('uses')"
        :breadcrumbs="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Uses', 'url' => route('uses')]
    ]"
>
    <section class="py-28 md:py-36 border-t border-neutral-900">
        <div class="text-center">
            <h1 class="font-heading text-3xl md:text-4xl font-bold text-neutral-300">What tools do I use?</h1>
            <span class="bg-coral inline-block h-0.5 rounded-full w-12 mt-4"></span>
        </div>

        <div class="max-w-4xl mx-auto px-6">
            <div class="post-content">
                @markdown(File::get(resource_path('markdown/uses.md')))
            </div>
        </div>
    </section>
</x-app-layout>
