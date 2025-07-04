<?php

namespace App\Filament\Stall\Pages;

use App\Enums\StallLocation;
use App\Enums\StallType;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;

class RegisterPage extends Register
{

    protected function handleRegistration(array $data): Model
    {
        $stallData = [
            'name' => $data['stall_name'],
            'type' => $data['stall_type'],
            'location' => $data['stall_location'],
        ];

        unset($data['stall_name'], $data['stall_type'], $data['stall_location']);

        $stallOwner = parent::handleRegistration($data);

        $stall = $stallOwner->stalls()->create($stallData);
        // $stallOwner->stalls()->attach($stall->id);
        $stallOwner->refresh();

        return $stallOwner;
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/register.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(Password::default())
            // ->dehydrateStateUsing(fn ($state) => Hash::make($state))
            ->same('passwordConfirmation')
            ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute'));
    }


    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        TextInput::make('contact_number')
                            ->label('Contact Number')
                            ->required()
                            ->maxLength(10)
                            ->minLength(10)
                            ->tel()
                            ->telRegex('/^(98|97)[0-9]{8}$/')
                            ->placeholder('Enter your contact number'),

                        TextInput::make('stall_name')
                            ->label('Stall Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter your stall name'),
                        Select::make('stall_type')
                            ->label('Stall Type')
                            ->options(StallType::class)
                            ->required()
                            ->placeholder('Select stall type'),

                        Select::make('stall_location')
                            ->label('Stall location')
                            ->options(StallLocation::class)
                            ->required()
                            ->placeholder('Select stall location'),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}