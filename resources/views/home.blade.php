@extends('layout')

@section('content')
    <section class="bg-dark-200 flex space-x-24 items-center justify-center py-32 min-h-screen bg-doodles">
        <img class="rounded-2xl w-72 block opacity-0 animate-slide-fade-in ![animation-delay:100ms]" src="{{ asset('dp.webp') }}" alt="Sergio Peris">

        <div class="text-center space-y-6">
            <h1 class="text-4xl md:text-5xl font-bold text-neutral-200 opacity-0 animate-slide-fade-in ![animation-delay:100ms]">Hi, I'm Sertxu</h1>
            <p class="text-xl md:text-2xl sm:text-3xl text-neutral-300 opacity-0 animate-slide-fade-in ![animation-delay:200ms]">Full&#8209;stack developer, open&#8209;source maintainer,<br>and content creator.</p>

            <div class="flex space-x-4 justify-center pt-4 opacity-0 animate-slide-fade-in ![animation-delay:300ms]">
                <a href="https://x.com/sertxudev" class="hover:bg-ocean hover:text-white border shadow-xs border-neutral-300 bg-white/5 text-neutral-300 flex h-12 items-center justify-center rounded-lg w-12 p-2.5">
                    <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.7497 3H20.8179L14.1165 10.624L22 21H15.8288L10.9917 14.7087L5.46371 21H2.39113L9.55758 12.8438L2 3H8.32768L12.6953 8.75048L17.7497 3ZM16.6719 19.174H18.3711L7.402 4.73077H5.57671L16.6719 19.174Z"/>
                    </svg>
                </a>
                <a href="https://github.com/sertxudev" class="hover:bg-ocean hover:text-white border shadow-xs border-neutral-300 bg-white/5 text-neutral-300 flex h-12 items-center justify-center rounded-lg w-12 p-2.5">
                    <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.68952 18.104C8.68952 18.1867 8.59677 18.2529 8.47984 18.2529C8.34677 18.2653 8.25403 18.1991 8.25403 18.104C8.25403 18.0213 8.34677 17.9551 8.46371 17.9551C8.58468 17.9427 8.68952 18.0089 8.68952 18.104ZM7.43548 17.9179C7.40726 18.0006 7.4879 18.0957 7.60887 18.1205C7.71371 18.1619 7.83468 18.1205 7.85887 18.0378C7.88306 17.9551 7.80645 17.86 7.68548 17.8228C7.58064 17.7938 7.46371 17.8352 7.43548 17.9179ZM9.21774 17.8476C9.10081 17.8765 9.02016 17.9551 9.03226 18.0502C9.04435 18.1329 9.14919 18.1867 9.27016 18.1577C9.3871 18.1288 9.46774 18.0502 9.45564 17.9675C9.44355 17.8889 9.33468 17.8352 9.21774 17.8476ZM11.871 2C6.27823 2 2 6.35477 2 12.0908C2 16.6772 4.81452 20.6019 8.83468 21.9832C9.35081 22.0783 9.53226 21.7516 9.53226 21.4827C9.53226 21.2263 9.52016 19.812 9.52016 18.9435C9.52016 18.9435 6.69758 19.5638 6.10484 17.7111C6.10484 17.7111 5.64516 16.5076 4.98387 16.1975C4.98387 16.1975 4.06048 15.5482 5.04839 15.5606C5.04839 15.5606 6.05242 15.6433 6.60484 16.6276C7.4879 18.2239 8.96774 17.7649 9.54435 17.4919C9.6371 16.8302 9.89919 16.3712 10.1895 16.0982C7.93548 15.8418 5.66129 15.5068 5.66129 11.5284C5.66129 10.3911 5.96774 9.82039 6.6129 9.09253C6.50806 8.82372 6.16532 7.71538 6.71774 6.28447C7.56048 6.01565 9.5 7.40108 9.5 7.40108C10.3065 7.16948 11.1734 7.04955 12.0323 7.04955C12.8911 7.04955 13.7581 7.16948 14.5645 7.40108C14.5645 7.40108 16.504 6.01152 17.3468 6.28447C17.8992 7.71951 17.5565 8.82372 17.4516 9.09253C18.0968 9.82453 18.4919 10.3952 18.4919 11.5284C18.4919 15.5192 16.1169 15.8377 13.8629 16.0982C14.2339 16.4249 14.5484 17.0453 14.5484 18.0171C14.5484 19.4108 14.5363 21.1354 14.5363 21.4745C14.5363 21.7433 14.7218 22.07 15.2339 21.9749C19.2661 20.6019 22 16.6772 22 12.0908C22 6.35477 17.4637 2 11.871 2ZM5.91935 16.2636C5.86694 16.305 5.87903 16.4001 5.94758 16.4787C6.0121 16.5449 6.10484 16.5738 6.15726 16.52C6.20968 16.4787 6.19758 16.3836 6.12903 16.305C6.06452 16.2388 5.97177 16.2099 5.91935 16.2636ZM5.48387 15.9287C5.45565 15.9824 5.49597 16.0486 5.57661 16.0899C5.64113 16.1313 5.72177 16.1189 5.75 16.061C5.77823 16.0072 5.7379 15.9411 5.65726 15.8997C5.57661 15.8749 5.5121 15.8873 5.48387 15.9287ZM6.79032 17.4009C6.72581 17.4547 6.75 17.5788 6.84274 17.6573C6.93548 17.7524 7.05242 17.7649 7.10484 17.6987C7.15726 17.6449 7.13306 17.5209 7.05242 17.4423C6.96371 17.3472 6.84274 17.3348 6.79032 17.4009ZM6.33065 16.793C6.26613 16.8343 6.26613 16.9419 6.33065 17.037C6.39516 17.1321 6.50403 17.1735 6.55645 17.1321C6.62097 17.0783 6.62097 16.9708 6.55645 16.8757C6.5 16.7806 6.39516 16.7392 6.33065 16.793Z"/>
                    </svg>
                </a>
                <a href="https://linkedin.com/in/sertxudev" class="hover:bg-ocean hover:text-white border shadow-xs border-neutral-300 bg-white/5 text-neutral-300 flex h-12 items-center justify-center rounded-lg w-12 p-2.5">
                    <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.47679 22H2.33036V8.64702H6.47679V22ZM4.40134 6.82555C3.07545 6.82555 2 5.72732 2 4.40139C2 3.7645 2.253 3.1537 2.70334 2.70335C3.15367 2.253 3.76446 2 4.40134 2C5.03821 2 5.649 2.253 6.09934 2.70335C6.54968 3.1537 6.80268 3.7645 6.80268 4.40139C6.80268 5.72732 5.72679 6.82555 4.40134 6.82555ZM21.9955 22H17.858V15.4999C17.858 13.9507 17.8268 11.9641 15.7022 11.9641C13.5464 11.9641 13.2161 13.6471 13.2161 15.3882V22H9.07411V8.64702H13.0509V10.4685H13.1089C13.6625 9.41936 15.0147 8.31219 17.0321 8.31219C21.2286 8.31219 22 11.0756 22 14.665V22H21.9955Z"/>
                    </svg>
                </a>
                <a href="mailto:hello@sertxu.dev" class="hover:bg-ocean hover:text-white border shadow-xs border-neutral-300 bg-white/5 text-neutral-300 flex h-12 items-center justify-center rounded-lg w-12 p-2.5">
                    <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 15.75C11.3543 15.75 10.7086 15.5382 10.1578 15.109L2 8.76562V17.625C2 18.6602 2.83945 19.5 3.875 19.5H20.125C21.1605 19.5 22 18.6605 22 17.625V8.76562L13.8438 15.1133C13.293 15.5391 12.6445 15.75 12 15.75ZM2.63633 7.67578L10.9254 14.125C11.5578 14.6172 12.4437 14.6172 13.0762 14.125L21.3652 7.67578C21.7305 7.36328 22 6.88281 22 6.375C22 5.33945 21.1602 4.5 20.125 4.5H3.875C2.83945 4.5 2 5.33945 2 6.375C2 6.88281 2.23477 7.36328 2.63633 7.67578Z"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <section class="bg-dark-100 py-24">
        <div class="max-w-screen-lg mx-auto">
            <h2 class="text-3xl text-neutral-200 text-center font-medium uppercase font-heading">About me</h2>
            <div class="border-b-2 border-ocean w-32 mx-auto mt-2 mb-10"></div>
            <div class="text-lg space-y-4 text-neutral-300 mx-auto max-w-screen-md">
                <p>My name is Sergio Peris, however I'm also known as Sertxu.
                <p>I'm a full&#8209;stack developer based in Spain, I love building web apps and making people's life easier.</p>
                <p>I have experience working with a variety of technologies, including Laravel, Node.js and C#. I'm also an open&#8209;source maintainer and content creator, and I love sharing my knowledge with others.</p>
                <p>I'm always looking for new challenges to try and learn new technologies to grow as a developer.</p>
            </div>
        </div>
    </section>

    {{--<section class="bg-dark-200 py-24">
        <div class="max-w-screen-lg mx-auto">
            <h2 class="text-2xl text-neutral-200 text-center font-medium">Education</h2>
            <div class="border-b-2 border-ocean w-32 mx-auto mt-2 mb-10"></div>
            <ul class="divide-y divide-neutral-500 *:py-6 gap-12 mx-auto max-w-lg">
                @foreach($education as $el)
                    <li>
                        <div class="flex items-center justify-between">
                            <p class="text-lg text-neutral-200">{{ $el->title }}</p>
                            <p class="text-neutral-400 text-sm">{{ $el->started_at->format('Y') }} - {{ $el->ended_at?->format('Y') ?? 'Present' }}</p>
                        </div>
                        <div class="text-neutral-300 [&_ul]:list-inside [&_ul]:list-disc [&_ul]:ml-4">
                            {!! Str::of($el->description)->markdown(); !!}
                        </div>
                        <p class="text-neutral-400 text-sm mt-1">{{ $el->location }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>--}}

    <section class="bg-dark-200 py-24">
        <div class="max-w-screen-xl mx-auto">
            <h2 class="text-3xl text-neutral-200 text-center font-medium uppercase font-heading">My latest posts</h2>
            <div class="border-b-2 border-ocean w-32 mx-auto mt-2 mb-10"></div>
            <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($posts as $post)
                    <x-post :post="$post" />
                @endforeach
            </ul>

            <div class="text-center mt-10">
                <a href="{{ route('posts.index') }}"
                   class="py-3 px-10 leading-tight text-sm font-medium border border-coral text-coral rounded-full hover:bg-coral hover:text-white uppercase transition ease-in-out duration-200">See More</a>
            </div>
        </div>
    </section>

    <section class="bg-dark-100 py-24">
        <div class="max-w-screen-xl mx-auto">
            <h2 class="text-3xl text-neutral-200 text-center font-medium uppercase font-heading">My latest projects</h2>
            <div class="border-b-2 border-ocean w-32 mx-auto mt-2 mb-10"></div>
            <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($projects as $project)
                    <x-project :project="$project" />
                @endforeach
            </ul>

            <div class="text-center mt-10">
                <a href="{{ route('projects.index') }}"
                   class="py-3 px-10 leading-tight text-sm font-medium border border-coral text-coral rounded-full hover:bg-coral hover:text-white uppercase transition ease-in-out duration-200">See More</a>
            </div>
        </div>
    </section>

    {{--<main class="grid md:grid-cols-2 gap-12 max-w-screen-lg px-6 mx-auto">
        <div class="space-y-24">
            <section class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-12 pb-6">
                <h2 class="text-2xl font-bold">Experience</h2>
                <ul class="divide-y *:py-6">
                    @foreach ($experiences as $experience)
                        <li>
                            <div class="flex items-center justify-between">
                                <p class="text-lg">{{ $experience->title }}</p>
                                <p class="text-neutral-600 text-sm">{{ $experience->started_at->format('Y') }} - {{ $experience->ended_at?->format('Y') ?? 'Present' }}</p>
                            </div>
                            <div class="text-sm [&_ul]:list-inside [&_ul]:list-disc [&_ul]:ml-4">
                                {!! Str::of($experience->description)->markdown(); !!}
                            </div>
                            <p class="text-neutral-600 text-sm">{{ $experience->location }}</p>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-12 pb-0">
                <h2 class="text-2xl font-bold">Latest Projects</h2>
                <ul class="divide-y *:py-6">
                    @foreach($projects as $project)
                        <li>
                            <a href="{{ $project->website }}" class="group focus-visible:outline-none">
                                <p class="text-neutral-800 font-medium group-hover:underline mb-2 group-focus-visible:underline">{{ $project->title }}</p>
                                <p class="text-neutral-600 mb-2">{{ $project->excerpt }}</p>
                            </a>
                        </li>
                    @endforeach

                    <li class="text-center pt-4">
                        <a href="{{ route('projects.index') }}" class="text-coral hover:underline focus-visible:outline-none focus-visible:underline">View more</a>
                    </li>
                </ul>
            </section>
        </div>
    </main>--}}
@endsection