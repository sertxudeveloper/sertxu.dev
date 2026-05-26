@props([
    'href' => null,
    'isActive' => false,
    'activeClass' => 'text-sm text-coral! underline underline-offset-[6px] transition-colors',
    'defaultClass' => 'text-sm text-neutral-300 hover:text-neutral-100 transition-colors',
])

<a href="{{ $href }}" @class([$attributes->get('class'), $defaultClass, $activeClass => $isActive])>{{ $slot }}</a>
