<?php

namespace App\Filament\Participant\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;

class ParticipantRegisterPage extends Register
{

    protected function handleRegistration(array $data): Model
    {
        $ParticipantData = [
            'name' => $data['name'],
            'contact_number' => $data['contact_number'],
            // 'college_name' => $data['college_name'],
            // 'team_name'=> $data['team_name'],
        ];

        $participant = parent::handleRegistration($data);
        $participant ->refresh();

        return $participant ;
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

                        // TextInput::make('college_name')
                        //     ->label('College Name') 
                        //     ->required()
                        //     ->maxLength(100),

                            // TextInput::make('team_name')
                            // ->label('Team Name')
                            // ->required()
                            // ->maxLength(255)
                            // ->placeholder('Enter your Team name'),

                            $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),

                       
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}