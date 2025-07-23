<?php
use Illuminate\Support\Facades\Route;
use App\Models\InventoryRecord;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return redirect()->route('filament.admin.pages.dashboard');
});

Route::get('/inventory/qr/{id}', function ($id) {
    $record = InventoryRecord::findOrFail($id);

    $data = [
        'Serial Number' => $record->temp_serial,
        'Quantity' => $record->qty,
        'Unit' => $record->unit,
        'Brand' => optional($record->brand)->name,
        'Model' => optional($record->model)->name,
        'Category' => optional($record->category)->name,
        'Department' => optional($record->department)->name,
        'Supplier' => optional($record->supplier)->name,
        'Location' => optional($record->location)->name,
        'Status' => $record->status,
        'Description' => $record->description,
        'Remarks' => $record->remarks,
        'Recorded At' => $record->recorded_at,
    ];

    $text = collect($data)->map(fn($v, $k) => "$k: $v")->implode("\n");

    $qr = new QrCode($text);
    $qr->setSize(800);

    $writer = new PngWriter();
    $result = $writer->write($qr);

    return Response::make(
        $result->getString(),
        200,
        [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $record->temp_serial . '.png"',
        ]
    );
})->name('inventory.qr.download');