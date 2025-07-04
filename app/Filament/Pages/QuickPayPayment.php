<?php

namespace App\Filament\Pages;

use App\Filament\Stall\Resources\StallOrderResource\Pages\ViewStallOrder;
use App\Filament\Widgets\WalletsOverviewWidget;
use App\Models\StallOrder;
use App\Models\StallOwner;
use App\Wallet\BaseAuthenticatableWalletHolder;
use App\Wallet\BaseModelWalletHolder;
use App\Wallet\Enums\WalletTransactionType;
use App\Wallet\QrHelper;
use App\Wallet\Transaction\BaseTransaction;
use App\Wallet\Transaction\StallOrderTransaction;
use Bavix\Wallet\Models\Wallet;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

class QuickPayPayment extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.quick-pay-payment';
    protected static bool $shouldRegisterNavigation = false;

    #[Url]
    public string $code;

    public ?array $formData = [];
    public ?string $cancelRedirectUrl = null;

    // Protected properties instead of public
    protected BaseTransaction $transaction;
    protected $walletHolder = null;
    protected $receiver = null;
    protected Wallet $wallet;
    protected ?StallOrder $stallOrder = null;

    protected bool $setupCompleted = false;

    public function mount()
    {
        $this->setup();
        $this->cancelRedirectUrl = Dashboard::getUrl();
        $this->form->fill();
    }

    public function hydrate()
    {
        $this->setup();
    }

    protected function setup(): void
    {
        if ($this->setupCompleted) {
            return;
        }

        $transaction = QrHelper::parse($this->code);
        abort_if(is_null($transaction), 404);

        $this->transaction = $transaction;

        $walletHolder = Filament::auth()->user();

        if ($walletHolder instanceof StallOwner) {
            $walletHolder = Filament::getTenant();
        }

        $this->walletHolder = $walletHolder;
        $this->wallet = $this->transaction->getWalletType()->of($this->walletHolder);

        if ($this->transaction instanceof StallOrderTransaction) {
            $this->stallOrder = $this->transaction->getStallOrder();
        }

        $this->receiver = $this->transaction->getPaymentTo();

        $this->setupCompleted = true;
    }

    // Getters for protected properties
    public function getTransaction(): ?BaseTransaction
    {
        return $this->transaction;
    }

    public function getStallOrder(): ?StallOrder
    {
        return $this->stallOrder;
    }

    public function getWalletHolder()
    {
        return $this->walletHolder;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function getRemainingAmount(): ?float
    {
        if (!$this->stallOrder) {
            return null;
        }
        return $this->stallOrder->getTotalAmount() - $this->stallOrder->getPaidAmount();
    }

    public function paymentInfolist(Infolist $infolist): Infolist
    {
        return match ($this->getTransaction()->getWalletTransactionType()) {
            WalletTransactionType::STALL_ORDER => $this->stallOrderInfolist($infolist),
            WalletTransactionType::WALLET_TO_WALLET => $this->walletToWalletInfolist($infolist),
            default => $infolist->schema([])->state([])
        };
    }

    public function stallOrderInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->getStallOrder())
            ->schema([
                Section::make('Order Details')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('orderedFor.name')
                            ->columnSpanFull(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('wallet_type')
                            ->badge(),
                        TextEntry::make('total_amount')
                            ->state(function (StallOrder $record) {
                                return $record->getTotalAmount() . " " . $record->wallet_type->getCurrency();
                            }),
                        TextEntry::make('paid_amount')
                            ->state(function (StallOrder $record) {
                                return $record->getPaidAmount() . " " . $record->wallet_type->getCurrency();
                            }),
                    ]),
                ViewStallOrder::getStallOrderItemsTableRepeater(),
            ]);
    }

    public function walletToWalletInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->getReceiver())
            ->schema([
                Section::make('Receiver Details')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->columnSpanFull(),
                        TextEntry::make('wallet_type')
                            ->state($this->getTransaction()->getWalletType())
                            ->badge(),
                    ]),
            ]);
    }

    public function getWalletToWalletFormSchema(): array
    {

        return [
            Hidden::make('minimum_amount')
                ->dehydrated(false)
                ->default(1),
            TextInput::make('amount')
                ->columnSpanFull()
                ->suffix($this->getTransaction()->getWalletType()->getCurrency())
                ->gt('minimum_amount')
                ->required(),
        ];
    }

    public function getStallOrderFormSchema(): array
    {

        return [
            Grid::make(2)
                ->schema([
                    Placeholder::make('total_amount')
                        ->content($this->stallOrder?->getTotalAmount() . " " . $this->stallOrder?->wallet_type->getCurrency()),
                    Placeholder::make('remaining_amount')
                        ->content($this->getRemainingAmount() . " " . $this->stallOrder?->wallet_type->getCurrency()),
                ]),

            Hidden::make('minimum_amount')
                ->dehydrated(false)
                ->default(1),
            Hidden::make('remaining_amount')
                ->dehydrated(false)
                ->default($this->getRemainingAmount()),
            TextInput::make('amount')
                ->columnSpanFull()
                ->default($this->getRemainingAmount())
                ->suffix($this->getTransaction()->getWalletType()->getCurrency())
                ->gt('minimum_amount')
                ->lte('remaining_amount')
                ->required(),
        ];
    }

    public function form(Form $form): Form
    {
        $schema =[];

        if ($this->getStallOrder()) {
            $schema = $this->getStallOrderFormSchema();
        } else {
            $schema = $this->getWalletToWalletFormSchema();
        }

        return $form
            ->schema($schema)
            ->statePath('formData');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WalletsOverviewWidget::class,
        ];
    }

    public function pay(): void
    {
        $data = $this->form->getState();
        $stallOrder = $this->getStallOrder();
        $wallet = $this->getWallet();
        $transaction = $this->getTransaction();

        $wallet->refresh();

        if ($data['amount'] > $wallet->balance) {
            Notification::make()
                ->title("Not enough balance")
                ->body("Your wallet does not have enough balance to complete this transaction.")
                ->danger()
                ->send();
            return;
        }

        $paymentTransaction = $transaction->payFrom($wallet, $data['amount']);

        if ($paymentTransaction) {
            Notification::make()
                ->title("Payment successful")
                ->body("You have successfully paid " . $data['amount'] . " " . $this->transaction->getWalletType()->getCurrency())
                ->success()
                ->send();

            redirect(Dashboard::getUrl());
        } else {
            Notification::make()
                ->title("Payment failed")
                ->body("You have failed to pay " . $data['amount'] . " " . $this->transaction->getWalletType()->getCurrency())
                ->danger()
                ->send();
        }
    }
}
