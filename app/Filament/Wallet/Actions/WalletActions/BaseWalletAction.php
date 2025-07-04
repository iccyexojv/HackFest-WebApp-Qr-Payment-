<?php

namespace App\Filament\Wallet\Actions\WalletActions;

use App\Models\Participant;
use App\Models\Stall;
use App\Models\User;
use App\Models\Visitor;
use App\Wallet\Enums\WalletHolderType;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

abstract class BaseWalletAction
{

    abstract public static function actionName(): string;
    abstract public static function form(): array;
    abstract public static function summary(): array;
    abstract public static function action(array $data): void;

    public static function formIsWizard(): bool
    {
        return false;
    }

    // Make method
    public static function make(bool $isTableAction = false): Action|TableAction
    {
        $actionName = ucwords(static::actionName());

        $action = Action::make(static::actionName());

        if($isTableAction){
            $action = TableAction::make(static::actionName());
        }

        return $action
            ->requiresConfirmation()
            ->action(function (array $data) {
                static::action($data);
            })
            ->steps([
                Step::make($actionName)
                    ->schema(static::form()),
                Step::make("Summary")
                    ->schema(static::summary()),
            ])
            ->slideOver();
    }


    // Helper

    public static function getHolderOptionsForHolderType(WalletHolderType $holderType): array
    {
        switch ($holderType->value) {
            case WalletHolderType::ORGANIZER->value:
                return User::select(['id', 'name', 'email'])
                    ->get()
                    ->mapWithKeys(function ($user) {
                        return [
                            $user->id => $user->name . ' (' . $user->email . ')'
                        ];
                    })
                    ->toArray();
                break;
            case WalletHolderType::PARTICIPANT->value:
                return Participant::select(['id', 'name', 'email'])
                    ->get()
                    ->mapWithKeys(function ($participant) {
                        return [
                            $participant->id => $participant->name . ' (' . $participant->email . ', ' . $participant->contact_number . ')'
                        ];
                    })
                    ->toArray();
                break;
            case WalletHolderType::VISITOR->value:
                return Visitor::select(['id', 'name', 'email'])
                    ->get()
                    ->mapWithKeys(function ($visitor) {
                        return [
                            $visitor->id => $visitor->name . ' (' . $visitor->email . ', ' . $visitor->contact_number . ')'
                        ];
                    })
                    ->toArray();
                break;

            case WalletHolderType::STALL->value:
                return Stall::select(['id', 'name', 'email'])
                    ->get()
                    ->mapWithKeys(function ($stall) {
                        return [
                            $stall->id => $stall->name . ' (' . $stall->type . ')'
                        ];
                    })
                    ->toArray();
                break;
            default:
                return [];
                break;
        }
    }

    public static function getProcessedByField(): Section
    {
        $processedBy = Auth::user();

        $content = "Unknown";

        if (!is_null($processedBy)) {
            $contentText = "ID: " . $processedBy->id . "<br>";
            $contentText .= "Name: " . $processedBy->name;
            $content = new HtmlString($contentText);
        }


        return Section::make("Processed By")
            ->schema([
                Placeholder::make('processed_by')
                    ->hiddenLabel()
                    ->content($content),
            ]);
    }
}
