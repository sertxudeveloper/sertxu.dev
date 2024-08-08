@extends('layout')

@section('content')
    <section class="max-w-screen-xl px-6 mx-auto md:mt-16 mt-8 mb-16">
        <div class="p-12">
            <h2 class="text-4xl font-bold text-center mb-16">My Projects</h2>

            <ul class="mx-auto max-w-screen-lg grid xl:grid-cols-2 grid-cols-1 gap-24">
                @foreach($projects as $project)
                    <li class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm hover:-translate-y-4 hover:scale-105 hover:z-10 transition transform">
                        <a class="block" href="{{ $project->repository }}">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($project->thumbnail) }}" alt="{{ $project->title }}" class="w-full h-64 object-cover block">
                            <div class="p-6">
                                <h3 class="text-2xl font-medium mb-2">{{ $project->title }}</h3>
                                <p class="text-gray-600">{{ $project->description }}</p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endsection
