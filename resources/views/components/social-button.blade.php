@props([
    'href' => null,
    'alt'
])

<div {{ $attributes }}>
    <a href="{{ $href }}" aria-label="{{ $alt }}" rel="noopener noreferrer"
       class="hover:bg-ocean hover:text-white border shadow-2xs border-neutral-300 bg-white/5 text-neutral-300 flex h-12 items-center justify-center rounded-lg w-12 p-2.5">
        {{ $slot }}
    </a>
</div>
