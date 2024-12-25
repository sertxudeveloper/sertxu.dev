@props([
    'education' => null,
])

<li>
    <div class="flex items-center justify-between mb-1">
        <p class="text-xl text-neutral-200">{{ $education->title }}</p>
        <p class="text-neutral-400 text-sm">{{ $education->started_at->format('Y') }}&nbsp;&#8209;&nbsp;{{ $education->ended_at?->format('Y') ?? 'Present' }}</p>
    </div>
    <div class="text-neutral-300 [&_ul]:list-inside [&_ul]:list-disc [&_ul]:ml-4">@markdown($education->description)</div>
    <div class="flex items-center mt-2 space-x-1">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 inline-block text-neutral-300">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
        </svg>
        <span class="text-neutral-400 text-sm">{{ $education->location }}</span>
    </div>
</li>
