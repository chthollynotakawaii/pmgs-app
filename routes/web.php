<?php

use Illuminate\Support\Facades\Route;
use App\Models\InventoryRecord;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inventory/qr/{id}', function ($id) {
    $record = InventoryRecord::findOrFail($id);

    $data = [
        'Serial Number' => $record->serial_number,
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
    ];

    $text = collect($data)->map(fn($v, $k) => "$k: $v")->implode("\n");

    $qr = new QrCode($text);
    $qr->setSize(800);

    $writer = new PngWriter();
    $result = $writer->write($qr);

    // Encode image to base64 for inline display
    $base64 = base64_encode($result->getString());
    $imageData = 'data:image/png;base64,' . $base64;

    return view('qr_preview', [
        'imageData' => $imageData,
        'downloadUrl' => route('inventory.qr.download', ['id' => $id]),
        'serial' => $record->serial_number
    ]);
})->name('inventory.qr');
Route::get('/inventory/qr/download/{id}', function ($id) {
    $record = InventoryRecord::findOrFail($id);

    $data = [
        'Serial Number' => $record->serial_number,
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
            'Content-Disposition' => 'attachment; filename="' . $record->serial_number . '.png"',
        ]
    );
})->name('inventory.qr.download');
