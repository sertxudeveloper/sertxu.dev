@props(['education'])

<li>
    <div class="flex items-center justify-between">
        <p class="text-lg text-gray-200">{{ $education->title }}</p>
        <p class="text-gray-400 text-sm">{{ $education->started_at->format('Y') }} - {{ $education->ended_at?->format('Y') ?? 'Present' }}</p>
    </div>
    <div class="text-gray-300 [&_ul]:list-inside [&_ul]:list-disc [&_ul]:ml-4">
        {!! Str::of($education->description)->markdown(); !!}
    </div>
    <p class="text-gray-400 text-sm">{{ $education->location }}</p>
</li>
