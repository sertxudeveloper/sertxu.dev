@extends('layout')

@section('content')
    <div class="grid grid-cols-4 py-12 gap-6 mx-auto max-w-screen-xl">
        <div class="col-span-1">
            {{--<p class="text-xl text-neutral-400 mb-4">Table of contents</p>--}}
            <aside class="top-[5rem] sticky [&_ul]:list-inside [&_ul]:list-disc -ml-4 [&_ul]:ml-4 text-neutral-300 [&_a:hover]:text-coral [&_li]:pb-1 [&_ul]:pt-1">
                <ul>
                    <li class="text-coral font-medium"><a href="#">Coffee</a></li>
                    <li><a href="#">Tea</a>
                        <ul>
                            <li><a href="#">Black tea</a></li>
                            <li><a href="#">Green tea</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Milk</a></li>
                </ul>
            </aside>
        </div>
        <div class="col-span-3">
            <h1 class="text-4xl font-medium text-neutral-200">{{ $post->title }}</h1>
            <div>
                <p class="text-lg text-neutral-300 mt-2">{{ $post->created_at->format('d M Y') }}</p>
            </div>

            <section class="markup">
                @markdown($post->content)
            </section>
        </div>
    </div>
@endsection
