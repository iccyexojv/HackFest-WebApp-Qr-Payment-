<?php

namespace App\Filament\Pages;

use App\Models\StallOrder;
use App\Models\StallOwner;
use App\Wallet\Enums\WalletType;
use App\Wallet\QrHelper;
use App\Wallet\Transaction\WalletToWalletTransaction;
use chillerlan\QRCode\QRCode;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;

class QuickPayQr extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static string $view = 'filament.pages.quick-pay-qr';

    protected static ?string $title = "Quick Pay";


    public ?string $scannedCode = null;
    public ?string $message = null; // Message to display to the user
    public ?string $messageType = null; // success, error, info

    protected static bool $shouldRegisterNavigation = false;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('quickReceive')
                ->requiresConfirmation()
                ->outlined()
                ->slideOver()
                ->modalDescription("Quickly receive a payment")
                ->color('success')
                ->modalCancelAction(false)
                ->modalCancelActionLabel("Go Back")
                ->infolist([
                    Tabs::make('Payment Type')
                        ->hiddenLabel()
                        ->schema([
                            $this->getImageEntryTab(WalletType::FEST_POINT),
                            $this->getImageEntryTab(WalletType::GAME_POINT),
                        ]),
                ]),
        ];
    }

    protected function getImageEntryTab(WalletType $walletType)
    {
        return Tab::make($walletType->getLabel())
            ->schema([
                ImageEntry::make(
                    'payment_qr_' .
                        str_replace('-', '_', $walletType->value)
                )
                    ->hiddenLabel()
                    ->size(250)
                    ->state(function () use ($walletType) {
                        $owner = Filament::auth()->user();

                        if ($owner instanceof StallOwner) {
                            $owner = Filament::getTenant();
                        }

                        if (is_null($owner)) {
                            return null;
                        }

                        $transaction = new WalletToWalletTransaction($owner, $walletType);

                        $qrcode = QrHelper::generate($transaction);

                        return $qrcode;
                    }),
            ]);
    }


    // A method to handle the scanned code from JavaScript
    public function handleScannedCode(string $code)
    {
        $this->scannedCode = $code;
        $this->message = null; // Clear previous messages

        $transaction = QrHelper::parse($this->scannedCode);

        if (is_null($transaction)) {
            Notification::make()
                ->title('Invalid QR Code')
                ->body("The scanned QR code is not valid. Please try again.")
                ->danger()
                ->send();

            // return redirect(request()->header('referer'));
            $this->js('window.location.reload()');

            return;
        }

        // Simulate processing time - replace with your actual logic
        // sleep(1);

        $this->message = "QR Code Scanned Successfully! Transaction Type: {$transaction->getWalletTransactionType()->getLabel()}";
        $this->messageType = 'success';

        // Send Filament Notification for success
        Notification::make()
            ->title('QR Code Scanned!')
            ->body("Successfully processed QR Code")
            ->success()
            ->send();

        return redirect(QuickPayPayment::getUrl([
            'code' => $code
        ]));
    }


    // Method to reset the scanner and messages (called from the "Try Again" button)
    // This method will now be less frequently used if you're refreshing on error.
    public function resetScanner()
    {
        $this->scannedCode = null;
        $this->message = null;
        $this->messageType = null;
        $this->dispatch('resumeScanner'); // Signal JS to resume/re-init scanner
    }
}
