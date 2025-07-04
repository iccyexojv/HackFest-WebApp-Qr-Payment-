<?php

namespace App\Wallet\Enums;

use App\Models\Participant;
use App\Models\Stall;
use App\Models\User;
use App\Models\Visitor;
use App\Wallet\BaseAuthenticatableWalletHolder;
use App\Wallet\BaseModelWalletHolder;
use Exception;
use Filament\Support\Contracts\HasLabel;

enum WalletHolderType: string implements HasLabel
{
    case ORGANIZER = 'organizer';
    case VISITOR = 'visitor';
    case PARTICIPANT = 'participant';
    case STALL = 'stall';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ORGANIZER => 'Organizer',
            self::VISITOR => 'Visitor',
            self::PARTICIPANT => 'Participant',
            self::STALL => 'Stall',
        };
    }

    public function getHolderClass(): string
    {
        return match ($this) {
            self::ORGANIZER => User::class,
            self::VISITOR => Visitor::class,
            self::PARTICIPANT => Participant::class,
            self::STALL => Stall::class,
        };
    }

    public function setupWallets(BaseModelWalletHolder|BaseAuthenticatableWalletHolder $walletHolder)
    {
        if ($walletHolder->getWalletHolderType() !== $this) {
            throw new Exception("Wallet holder type mismatch");
        }

        match ($this) {
            self::ORGANIZER => $this->setupOrganizerWallet($walletHolder),
            self::VISITOR => $this->setupVisitorWallet($walletHolder),
            self::PARTICIPANT => $this->setupParticipantWallet($walletHolder),
            self::STALL => $this->setupStallWallet($walletHolder),
        };
    }

    // Wallet setup for each type of wallet holder

    protected function setupOrganizerWallet(BaseModelWalletHolder|BaseAuthenticatableWalletHolder $walletHolder)
    {
        (WalletType::FEST_POINT)->createWallet($walletHolder, 0);
        (WalletType::GAME_POINT)->createWallet($walletHolder, 0);
        (WalletType::KBC_POINT)->createWallet($walletHolder, 60000);
    }

    protected function setupStallWallet(BaseModelWalletHolder|BaseAuthenticatableWalletHolder $walletHolder)
    {
        (WalletType::FEST_POINT)->createWallet($walletHolder);
        (WalletType::GAME_POINT)->createWallet($walletHolder);
        (WalletType::KBC_POINT)->createWallet($walletHolder);
    }

    protected function setupParticipantWallet(BaseModelWalletHolder|BaseAuthenticatableWalletHolder $walletHolder)
    {
        (WalletType::FEST_POINT)->createWallet($walletHolder, 0);
        (WalletType::GAME_POINT)->createWallet($walletHolder);
        (WalletType::KBC_POINT)->createWallet($walletHolder, 60000);
    }

    protected function setupVisitorWallet(BaseModelWalletHolder|BaseAuthenticatableWalletHolder $walletHolder)
    {
        (WalletType::FEST_POINT)->createWallet($walletHolder);
        (WalletType::GAME_POINT)->createWallet($walletHolder);
        (WalletType::KBC_POINT)->createWallet($walletHolder, 50000);
    }
}
