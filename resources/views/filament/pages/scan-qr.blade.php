<x-filament::page>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white font-[Poppins]">Scan Inventory QR Code</h2>
    </div>

    <div class="flex justify-center px-4">
        <div id="reader" class="rounded-xl shadow-lg overflow-hidden border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 w-full max-w-md"></div>
    </div>

    <div class="mt-6 font-[Poppins] px-4 flex justify-center">
        <div class="w-full max-w-6xl">
            <label class="font-semibold text-lg text-gray-800 dark:text-gray-200 block mb-2">Scanned Data:</label>
            <pre
                id="qr-result"
                class="min-h-[200px] p-6 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 border dark:border-gray-700 rounded-lg text-base leading-relaxed w-full resize-y overflow-auto"
            ></pre>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const qrResult = document.getElementById('qr-result');
            const scanner = new Html5Qrcode("reader");

            Html5Qrcode.getCameras().then(devices => {
                if (devices.length > 0) {
                    scanner.start(
                        devices[0].id,
                        { fps: 10, qrbox: 250 },
                        qrText => {
                            qrResult.textContent = qrText;
                        },
                        error => {
                            // Ignore scan errors
                        }
                    );
                } else {
                    qrResult.textContent = 'No camera found.';
                }
            }).catch(error => {
                qrResult.textContent = 'Camera error: ' + error;
            });
        });
    </script>
</x-filament::page>
