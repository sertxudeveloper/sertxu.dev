@props([
    'experience' => null,
])

<li>
    <div class="flex items-center justify-between mb-1">
        <p class="text-xl text-neutral-200">{{ $experience->title }}</p>
        <p class="text-neutral-400 text-sm">{{ $experience->started_at->format('Y') }}&nbsp;&#8209;&nbsp;{{ $experience->ended_at?->format('Y') ?? 'Present' }}</p>
    </div>
    <div class="text-neutral-300 [&_ul]:list-inside [&_ul]:list-disc [&_ul]:ml-4">@markdown($experience->description)</div>
    <div class="flex items-center mt-2 space-x-1">
        <x-heroicon-o-map-pin class="w-4 h-4 inline-block text-neutral-300" />
        <span class="text-neutral-400 text-sm">{{ $experience->location }}</span>
    </div>
</li>
