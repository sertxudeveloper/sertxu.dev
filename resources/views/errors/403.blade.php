<x-app-layout>
    <section class="bg-dark-200 py-24 px-4 relative grid items-center min-h-[calc(100vh-180px)]">
        <div class="top-0 left-0 absolute h-full w-full z-0 pointer-events-none bg-doodles"></div>

        <div class="text-center">
            <h2 class="text-7xl text-neutral-200 font-medium uppercase font-heading">403</h2>
            <div class="border-b-2 border-ocean w-32 mx-auto mt-2 mb-8"></div>
            <p class="text-2xl text-neutral-300">{{ isset($exception) ? $exception->getMessage() : 'You are not allowed to access this page.' }}</p>
        </div>
    </section>
</x-app-layout>
