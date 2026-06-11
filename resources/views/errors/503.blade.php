@extends('errors.minimal')

@section('content')
    <section class="relative min-h-[calc(100vh-80px)] flex items-center justify-center px-6 overflow-hidden">
        <div class="glow-top-right"></div>
        <div class="glow-bottom-left"></div>

        <div class="text-center">
            <h2 class="text-8xl font-heading font-bold text-neutral-200">503</h2>
            <span class="bg-coral inline-block h-0.5 rounded-full w-16 mt-4 mb-6"></span>
            <p class="text-xl md:text-2xl text-neutral-400">The service is temporarily unavailable. Please try again later.</p>
            <a href="{{ route('home') }}" class="inline-block mt-10 px-6 py-3 bg-coral text-white rounded-lg text-sm font-medium hover:shadow-[0_0_10px_#FF3047] transition-all">
                Go back home
            </a>
        </div>
    </section>
@endsection
