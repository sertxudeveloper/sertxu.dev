@props([
    'href' => null,
])

<div {{ $attributes->merge(['class' => 'text-center']) }}>
    <a class="py-3 px-10 leading-tight text-sm font-medium border border-coral text-coral rounded-full hover:bg-coral hover:text-white uppercase transition ease-in-out duration-200"
       href="{{ $href }}">See More</a>
</div>
