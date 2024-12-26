@extends('errors.minimal')

@section('content')
    <section class="bg-dark-200 py-24 px-4 relative grid items-center min-h-[calc(100vh-180px)]">
        <div class="top-0 left-0 absolute h-full w-full z-0 pointer-events-none bg-doodles"></div>

        <div class="text-center mb-12">
            <h2 class="text-7xl text-neutral-200 font-medium uppercase font-heading">418</h2>
            <div class="border-b-2 border-ocean w-32 mx-auto mt-2 mb-8"></div>
            <p class="text-2xl text-neutral-300">Hey, I'm a teapot!</p>
        </div>

        <iframe width="720" height="405" class="mx-auto block"
                src="https://www.youtube.com/embed/bDMLKgAwZLI?si=MxQxOz7AG42Pdv0h&autoplay=1&mute=1"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </section>
@endsection
