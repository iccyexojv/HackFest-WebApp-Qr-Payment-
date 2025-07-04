<x-filament-panels::page>
    <div class="flex flex-col h-full">
        <div class="flex-grow p-6 rounded-lg shadow-md mb-4 overflow-y-auto">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Stall Order Details</h2>
            <div class="border-b border-gray-200 pb-4 mb-6">
                {{ $this->paymentInfolist }}
            </div>
        </div>

        <div class="p-6 rounded-lg shadow-md sticky bottom-0 z-10 w-full">
            <x-filament::section>
                <form wire:submit="pay" class="space-y-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Payment Information</h2>
                    {{ $this->form }}

                    @php
                        $remainingAmount = $this->getRemainingAmount();
                        $wallet = $this->getWallet();
                    @endphp

                    @if (is_null($remainingAmount) || $remainingAmount > 0)
                        <div class="flex flex-col sm:flex-row justify-end gap-4 mt-6">
                            <x-filament::button
                                size="xl"
                                type="submit"
                                color="primary"
                                icon="heroicon-o-currency-dollar"
                                class="w-full sm:w-auto"
                                :disabled="$wallet && $wallet->balance < $remainingAmount"
                            >
                                Pay Now
                            </x-filament::button>

                            <x-filament::button
                                size="xl"
                                color="danger"
                                href="{{ $cancelRedirectUrl }}"
                                tag="a"
                                icon="heroicon-o-x-circle"
                                class="w-full sm:w-auto"
                            >
                                Cancel
                            </x-filament::button>
                        </div>
                    @endif
                </form>
            </x-filament::section>
        </div>
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>
