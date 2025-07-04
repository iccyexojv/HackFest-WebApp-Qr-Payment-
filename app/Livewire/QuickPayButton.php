<?php

namespace App\Livewire;

use App\Filament\Pages\QuickPayQr;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class QuickPayButton extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function quickPayAction(): Action
    {
        return Action::make('quickPay')
            ->outlined()
            ->url(QuickPayQr::getUrl());
    }

    public function render()
    {
        return view('livewire.quick-pay-button');
    }
}
