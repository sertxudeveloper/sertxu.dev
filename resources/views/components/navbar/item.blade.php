@props([
    'href' => null,
    'isActive' => false,
    'activeClass' => 'text-coral! underline underline-offset-8',
    'defaultClass' => 'px-3 py-3 hover:text-white outline-hidden focus:underline',
])

<a href="{{ $href }}" wire:navigate @class([$defaultClass, $activeClass => $isActive])>{{ $slot }}</a>
