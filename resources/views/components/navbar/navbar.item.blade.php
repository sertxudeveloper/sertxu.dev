@props([
    'href' => null,
    'isActive' => false,
    'activeClass' => 'font-bold !text-coral',
    'defaultClass' => 'px-3 py-3 hover:text-white outline-none focus:underline',
])

<a href="{{ $href }}" wire:navigate @class([$defaultClass, $activeClass => $isActive])>{{ $slot }}</a>