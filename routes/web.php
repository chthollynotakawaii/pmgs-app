<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\InventoryRecord;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\NotoSans;

Route::get('/', function () {
    return redirect()->route('filament.admin.pages.dashboard');
});
Route::get('/inventory/qr/{id}', function ($id) {
    $record = InventoryRecord::findOrFail($id);

    $data = [
        'Quantity' => $record->qty,
        'Unit' => $record->unit,
        'Brand' => optional($record->brand)->name,
        'Model' => optional($record->model)->name,      
        'Description' => $record->description,
        'Serial Number' => $record->temp_serial,
        'Category' => optional($record->category)->name,
        'Department' => optional($record->department)->name,
        'Supplier' => optional($record->supplier)->name,
        'Location' => optional($record->location)->name,
        'Status' => $record->status,
        'Remarks' => $record->remarks,
        'Recorded At' => $record->recorded_at,
    ];

    $baseControl = preg_replace('/-\d+$/', '', $record->control_number);
    $uniqueId = uniqid();
    $folder = storage_path("app/qrs/{$uniqueId}");
    mkdir($folder, 0755, true);

    $writer = new PngWriter();

    for ($i = 1; $i <= $record->qty; $i++) {
        $control = $baseControl . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
        $text = collect($data)->prepend($control, 'Control Number')->map(fn($v, $k) => "$k: $v")->implode("\n");

        $qr = QrCode::create($text)->setSize(800);
        $logo = Logo::create(public_path('images/logo.png'))->setResizeToWidth(200);
        $label = Label::create($control)
            ->setTextColor(new \Endroid\QrCode\Color\Color(0, 0, 0))
            ->setFont(new NotoSans(20));

        $result = $writer->write($qr, $logo, $label);
        file_put_contents("{$folder}/{$control}.png", $result->getString());
    }

    $zipPath = storage_path("app/qrs/{$baseControl}.zip");
    $zip = new ZipArchive;
    $zip->open($zipPath, ZipArchive::CREATE);

    foreach (glob("{$folder}/*.png") as $file) {
        $zip->addFile($file, basename($file));
    }

    $zip->close();

    foreach (glob("{$folder}/*.png") as $file) {
        unlink($file);
    }
    rmdir($folder);

    return response()->download($zipPath, "{$baseControl}.zip", [
        'Content-Type' => 'application/zip',
        'Content-Disposition' => 'attachment; filename="' . $baseControl . '.zip"',
        'Cache-Control' => 'no-store, no-cache, must-revalidate',
        'Pragma' => 'no-cache',
    ])->deleteFileAfterSend(true);
})->name('inventory.qr.download');
