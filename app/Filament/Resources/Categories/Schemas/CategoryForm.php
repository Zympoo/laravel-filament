<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Naam')
                    ->required()
                    ->placeholder('Bijv. Laravel, PHP, AI...')
                    ->helperText('Geef een duidelijke categorienaam')
                    ->minLength(2)
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
            ]);
    }
}
