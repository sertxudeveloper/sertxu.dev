@extends('layout')

@section('content')
    <section class="bg-dark-200 py-24">
        <div class="max-w-screen-xl mx-auto">
            <h2 class="text-2xl text-gray-200 text-center font-medium">Articles</h2>
            <div class="border-b-2 border-ocean w-32 mx-auto mt-2 mb-10"></div>
            @livewire('posts')
        </div>
    </section>
@endsection
