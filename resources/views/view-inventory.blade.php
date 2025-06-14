<x-filament::page>
    <div class="space-y-6">
        <h2 class="text-2xl font-bold">Inventory Record: {{ $record->serial_number }}</h2>

        <div class="grid grid-cols-2 gap-4">
            <div><strong>Quantity:</strong> {{ $record->qty }}</div>
            <div><strong>Unit:</strong> {{ $record->unit }}</div>
            <div><strong>Brand:</strong> {{ optional($record->brand)->name }}</div>
            <div><strong>Model:</strong> {{ optional($record->model)->name }}</div>
            <div><strong>Category:</strong> {{ optional($record->category)->name }}</div>
            <div><strong>Department:</strong> {{ optional($record->department)->name }}</div>
            <div><strong>Status:</strong> {{ $record->status }}</div>
            <div><strong>Location:</strong> {{ optional($record->location)->name }}</div>
        </div>

        <div>
            <strong>Description:</strong>
            <div class="border p-2 rounded">{{ $record->description }}</div>
        </div>

        <div>
            <strong>Remarks:</strong>
            <div class="border p-2 rounded">{{ $record->remarks }}</div>
        </div>

        <div>
            <a href="{{ route('inventory.qr', ['id' => $record->id]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Preview QR Code
            </a>
        </div>
    </div>
</x-filament::page>
