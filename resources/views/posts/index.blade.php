@extends('layout')

@section('content')
    <section class="bg-dark-200 py-24 min-h-screen bg-doodles">
        <div class="max-w-screen-xl mx-auto">
            <h2 class="text-3xl text-neutral-200 text-center font-medium uppercase font-heading">Blog</h2>
            <div class="border-b-2 border-ocean w-32 mx-auto mt-2 mb-10"></div>
            @livewire('posts')
        </div>
    </section>
@endsection