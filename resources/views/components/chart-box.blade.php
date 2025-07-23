@props(['title', 'type', 'data'])

@php
    $chartHeight = in_array($type, ['pie', 'radar']) ? 'h-[300px]' : 'h-[400px]';
    $colors = $data['datasets'][0]['backgroundColor'] ?? [];
    $labels = $data['labels'] ?? [];
@endphp

<div class="bg-white dark:bg-gray-900 p-4 rounded-lg shadow-md">
    <h3 class="text-md font-semibold text-gray-700 dark:text-gray-200 mb-3">{{ $title }}</h3>

    @if($type === 'pie')
        <div class="flex flex-wrap gap-4 mb-4">
            @foreach($labels as $index => $label)
                <div class="flex items-center gap-2 text-sm">
                    <span class="w-4 h-4 inline-block rounded" style="background-color: {{ $colors[$index] ?? '#ccc' }}"></span>
                    <span class="text-gray-800 dark:text-white">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <div class="w-full {{ $chartHeight }}">
        <canvas x-data x-init="
            new Chart($el, {
                type: '{{ $type }}',
                data: @js($data),
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        title: { display: false },
                    },
                    scales: {
                        x: { display: ['bar', 'line'].includes('{{ $type }}') },
                        y: { display: ['bar', 'line'].includes('{{ $type }}') },
                    },
                },
            });
        "></canvas>
    </div>
</div>
