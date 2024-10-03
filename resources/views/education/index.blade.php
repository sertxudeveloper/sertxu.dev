<x-app-layout>
    <section class="bg-dark-200 py-24 px-4 min-h-screen bg-doodles">
        <div class="max-w-screen-xl mx-auto">
            <h2 class="text-3xl text-neutral-200 text-center font-medium uppercase font-heading">My Education</h2>
            <div class="border-b-2 border-ocean w-32 mx-auto mt-2 mb-10"></div>
            <ul class="max-w-lg mx-auto *:py-4 divide-y divide-neutral-700">
                @foreach($education as $item)
                    <x-education :education="$item" />
                @endforeach
            </ul>
        </div>
    </section>
</x-app-layout>
