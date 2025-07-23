<x-filament::page>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white font-[Poppins]">Scan Inventory QR Code</h2>
    </div>

    <div class="flex justify-center px-4">
        <div id="reader" class="rounded-xl shadow-lg overflow-hidden border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 w-full max-w-md"></div>
    </div>

<div class="flex justify-center mt-4 hidden" id="flip-wrapper">
    <button id="flip-camera" class="bg-white dark:bg-gray-800 p-2 rounded shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black dark:text-white" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M16.5 3.75H18a2.25 2.25 0 0 1 2.25 2.25v1.5M7.5 20.25H6A2.25 2.25 0 0 1 3.75 18v-1.5M3.75 8.25v-3A2.25 2.25 0 0 1 6 3h1.5M20.25 15.75v3A2.25 2.25 0 0 1 18 21h-1.5M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0z"/>
        </svg>
    </button>
</div>





    <div class="mt-6 font-[Poppins] px-4 flex justify-center">
        <div class="w-full max-w-6xl">
            <label class="font-semibold text-lg text-black-800 dark:text-gray-200 block mb-2">Scanned Data:</label>
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
            const flipBtn = document.getElementById('flip-camera');
            const scanner = new Html5Qrcode("reader");

            let currentCamera = 0;
            let cameras = [];

            function startScanner(cameraId) {
                scanner.start(
                    cameraId,
                    { fps: 10, qrbox: 250 },
                    qrText => {
                        qrResult.textContent = qrText;
                    },
                    error => {
                        // Ignore errors
                    }
                );
            }

            Html5Qrcode.getCameras().then(devices => {
                if (devices.length === 0) {
                    qrResult.textContent = 'No camera found.';
                    return;
                }

                cameras = devices;

                const rearCamIndex = devices.findIndex(cam => /back|rear/i.test(cam.label));
                const hasRearCamera = rearCamIndex !== -1;

                if (hasRearCamera) {
                    document.getElementById('flip-wrapper').classList.remove('hidden');
                }

                currentCamera = hasRearCamera ? rearCamIndex : 0;
                startScanner(cameras[currentCamera].id);

                if (hasRearCamera) {
                    flipBtn.addEventListener('click', () => {
                        scanner.stop().then(() => {
                            currentCamera = (currentCamera + 1) % cameras.length;
                            startScanner(cameras[currentCamera].id);
                        });
                    });
                }
            }).catch(error => {
                qrResult.textContent = 'Camera error: ' + error;
            });
        });
    </script>
</x-filament::page>