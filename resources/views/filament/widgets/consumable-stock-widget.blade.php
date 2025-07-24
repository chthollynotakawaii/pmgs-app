<x-filament::widget>
    <x-filament::card>
        <h3 class="text-lg font-bold">Low Stock Items</h3>
        <ul>
            @forelse ($this->lowStock as $item)
                <li>{{ $item->name }} ({{ $item->stock }} {{ $item->unit ?? 'units' }})</li>
            @empty
                <li>All stocks are sufficient.</li>
            @endforelse
        </ul>
    </x-filament::card>
</x-filament::widget>
