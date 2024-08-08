@extends('layout')

@section('content')
    <section class="max-w-screen-xl px-6 mx-auto md:mt-16 mt-8 mb-16">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-12">
            <h2 class="text-4xl font-bold text-center mb-16">My Projects</h2>

            <ul class="mx-auto max-w-screen-lg grid xl:grid-cols-2 grid-cols-1 gap-24">
                @foreach($projects as $project)
                    <li class="">
                        <a class="group" href="{{ $project->repository }}">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($project->thumbnail) }}" alt="{{ $project->title }}" class="w-full h-64 object-cover rounded-2xl mb-4 block group-hover:-translate-y-4 group-hover:scale-105 group-hover:z-10 transition transform">
                            <h3 class="text-2xl font-medium mb-2">{{ $project->title }}</h3>
                            <p class="text-gray-600">{{ $project->description }}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endsection
