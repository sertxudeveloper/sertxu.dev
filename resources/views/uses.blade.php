<x-app-layout>
    <section class="bg-dark-200 py-24 px-4 min-h-[calc(100vh-180px)] relative">
        <div class="top-0 left-0 absolute h-full w-full z-0 pointer-events-none bg-doodles"></div>

        <div class="max-w-screen-md mx-auto">
            <h2 class="text-3xl text-neutral-200 text-center font-medium uppercase font-heading">Uses</h2>
            <div class="border-b-2 border-ocean w-32 mx-auto mt-2 mb-10"></div>
            <section class="markup !-mt-6">@markdown(File::get(resource_path('markdown/uses.md')))</section>
        </div>
    </section>
</x-app-layout>
