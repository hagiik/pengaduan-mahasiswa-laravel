<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('username')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                
                $this->getNameFormComponent()
                    ->maxLength(255),
                    
                $this->getEmailFormComponent()
                    ->maxLength(255),
                    
                $this->getPasswordFormComponent(),
                
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->columns(1); // Single column layout for cleaner form
    }
}