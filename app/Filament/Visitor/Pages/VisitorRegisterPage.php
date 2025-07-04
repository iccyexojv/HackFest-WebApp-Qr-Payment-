<?php

namespace App\Filament\Visitor\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;

class VisitorRegisterPage extends Register
{

    protected function handleRegistration(array $data): Model
    {
        $visitorData = [
            'name' => $data['name'],
            'email'=> $data['email'],
            'contact_number' => $data['contact_number'],
            
        ];

        $visitor = parent::handleRegistration($data);

// $Visitor = Visitor::create($visitorData);
        // $visitor->visitors()->attach($visitor->id);
        $visitor->refresh();

        return $visitor;
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
                            ->tel()
                            ->telRegex('/^(98|97)[0-9]{8}$/')
                            ->placeholder('Enter your contact number'),
                            $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),

                       
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}