@props([
    'href' => null,
])

<div {{ $attributes }}>
    <a href="{{ $href }}" class="hover:bg-ocean hover:text-white border shadow-xs border-neutral-300 bg-white/5 text-neutral-300 flex h-12 items-center justify-center rounded-lg w-12 p-2.5">
        {{ $slot }}
    </a>
</div>
