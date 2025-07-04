<x-filament-panels::page>

    <style wire:ignore>
        img[alt="Info icon"] {
            display: none !important;
            height: 0px !important;
            width: 0px !important;
        }

        /* Optional: Add a subtle border or shadow to the scanner area */
        #reader-container {
            border: 2px solid #e2e8f0;
            /* Tailwind's gray-200 */
            border-radius: 0.5rem;
            /* Tailwind's rounded-lg */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            /* Ensures rounded corners apply to scanner output */
            background-color: #f8fafc;
            /* Tailwind's gray-50 */
        }

        @media (prefers-color-scheme: dark) {
            #reader-container {
                border-color: #374151;
                /* Tailwind's gray-700 */
                background-color: #1f2937;
                /* Tailwind's gray-800 */
            }
        }


        /* Styles for status messages */
        .scan-message {
            padding: 1rem;
            margin-top: 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-align: center;
        }

        .scan-message.success {
            background-color: #d1fae5;
            /* Tailwind's green-100 */
            color: #065f46;
            /* Tailwind's green-800 */
        }

        @media (prefers-color-scheme: dark) {
            .scan-message.success {
                background-color: #10b98130;
                /* Green with opacity */
                color: #34d399;
                /* Tailwind's green-400 */
            }
        }

        .scan-message.error {
            background-color: #fee2e2;
            /* Tailwind's red-100 */
            color: #991b1b;
            /* Tailwind's red-800 */
        }

        @media (prefers-color-scheme: dark) {
            .scan-message.error {
                background-color: #ef444430;
                /* Red with opacity */
                color: #f87171;
                /* Tailwind's red-400 */
            }
        }

        .scan-message.info {
            background-color: #dbeafe;
            /* Tailwind's blue-100 */
            color: #1e40af;
            /* Tailwind's blue-800 */
        }

        @media (prefers-color-scheme: dark) {
            .scan-message.info {
                background-color: #3b82f630;
                /* Blue with opacity */
                color: #60a5fa;
                /* Tailwind's blue-400 */
            }
        }
    </style>

    <div class="flex flex-col items-center justify-center min-h-[calc(100vh-theme(spacing.16))] p-4 sm:p-6 lg:p-8" wire:ignore>
        <x-filament::section class="max-w-xl w-full">
            <x-slot name="heading">
                QR Code Scanner
            </x-slot>

            <x-slot name="headerEnd">
            </x-slot>

            <x-slot name="description">
                Scan a QR code using your device's camera.
            </x-slot>

            <div id="reader-container"
                class="flex-none w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                <div id="reader"></div>
            </div>

            <div class="mt-4 text-center">
                <p class="text-gray-600 dark:text-gray-400 text-lg" x-data="{ message: 'Initializing camera...' }" x-text="message"
                    x-ref="instructionMessage"></p>

                {{-- Livewire driven status messages --}}
                @if ($message)
                    <div class="scan-message {{ $messageType }}">
                        {{ $message }}
                    </div>
                @endif

                {{-- The "Try Again" button only appears for error messages --}}
                @if ($messageType === 'error')
                    <x-filament::button wire:click="resetScanner" class="mt-4">
                        <x-filament::icon icon="heroicon-o-arrow-path" class="w-4 h-4 mr-2" />
                        Scan Again
                    </x-filament::button>
                @endif
            </div>
        </x-filament::section>

        <script src="/js/html5-qrcode.min.js" type="text/javascript" wire:ignore></script>
        <script wire:ignore>
            let html5QrcodeScanner = null;
            let scannerInitialized = false; // Flag to track if scanner has been rendered

            function onScanSuccess(decodedText, decodedResult) {
                // Pause scanner and inform user immediately
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.pause(true);
                }
                document.querySelector('[x-ref="instructionMessage"]').textContent = 'QR Code detected. Processing...';

                // Call Livewire component method
                @this.call('handleScannedCode', decodedText);
            }

            function onScanFailure(error) {
                // This function typically fires continuously, so avoid spamming UI updates here.
                // console.warn(`Code scan error = ${error}`);
            }

            // Function to initialize or re-initialize the scanner
            async function initScanner() {
                document.querySelector('[x-ref="instructionMessage"]').textContent = 'Initializing camera...';

                // Clear any existing scanner instance completely
                if (html5QrcodeScanner && scannerInitialized) {
                    try {
                        await html5QrcodeScanner.clear(); // Await the clear operation
                    } catch (err) {
                        console.error("Failed to clear html5QrcodeScanner, proceeding anyway: ", err);
                    } finally {
                        html5QrcodeScanner = null; // Ensure nullified
                        scannerInitialized = false; // Reset flag
                    }
                }

                renderScanner(); // Always render after ensuring previous is cleared
            }

            // Function to render the scanner
            function renderScanner() {
                // Ensure the reader div is empty before rendering
                const readerDiv = document.getElementById('reader');
                if (readerDiv) {
                    readerDiv.innerHTML = ''; // Clear content to prevent old streams/elements
                } else {
                    console.error("QR reader element with ID 'reader' not found!");
                    document.querySelector('[x-ref="instructionMessage"]').textContent = 'Error: Scanner container not found.';
                    return; // Exit if the div doesn't exist
                }

                html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader", {
                        fps: 10,
                        showTorchButtonIfSupported: true,
                        showZoomSliderIfSupported: true,
                        qrbox: {
                            width: 250,
                            height: 250
                        },
                        rememberLastUsedCamera: true // Improves UX by remembering camera choice
                    },
                    /* verbose= */
                    false
                );

                // Call render and handle the promise
                html5QrcodeScanner.render(onScanSuccess, onScanFailure)
                    .then(() => {
                        scannerInitialized = true; // Set flag after successful render
                        document.querySelector('[x-ref="instructionMessage"]').textContent =
                            'Position the QR code within the frame.';
                    })
                    .catch((err) => {
                        console.error("Failed to render html5QrcodeScanner: ", err);
                        document.querySelector('[x-ref="instructionMessage"]').textContent =
                            'Camera failed to load. Please ensure camera access is granted and no other app is using it.';
                        // Dispatch a Livewire event to show a Filament Notification here
                        @this.dispatch('camera-error', {
                            message: 'Camera failed to load. Check permissions.'
                        });
                    });
            }

            // --- IMPORTANT: Wait for Livewire to be initialized ---
            document.addEventListener('livewire:initialized', () => {
                initScanner(); // Initialize the scanner once Livewire is ready

                // Livewire event listener to resume/re-initialize the scanner
                window.Livewire.on('resumeScanner', () => {
                    initScanner(); // Re-initialize to guarantee fresh start
                    @this.set('message', null); // Clear messages when resuming
                    @this.set('messageType', null);
                });

                // Livewire event listener to clear the scanner (e.g., if navigating away or component is destroyed)
                window.Livewire.on('clearScanner', async () => {
                    if (html5QrcodeScanner && scannerInitialized) {
                        try {
                            await html5QrcodeScanner.clear();
                        } catch (err) {
                            console.error("Error clearing scanner on Livewire 'clearScanner' event:", err);
                        } finally {
                            html5QrcodeScanner = null;
                            scannerInitialized = false;
                            document.querySelector('[x-ref="instructionMessage"]').textContent =
                                ''; // Clear message
                        }
                    }
                });

                // You can listen for a camera error event from Livewire if needed (and defined in PHP)
                window.Livewire.on('camera-error', ({
                    message
                }) => {
                    console.error("Received camera-error event:", message);
                    // You might want to update the UI more prominently here
                    // e.g., @this.set('message', message); @this.set('messageType', 'error');
                });
            });
        </script>
    </div>
</x-filament-panels::page>
