@props([
    'href' => null,
    'isActive' => false,
    'activeClass' => 'text-sm text-[#e02a3f] underline underline-offset-[6px] transition-colors',
    'defaultClass' => 'text-sm text-neutral-400 hover:text-text-primary transition-colors',
])

<a href="{{ $href }}" @class([$defaultClass, $activeClass => $isActive])>{{ $slot }}</a>
