<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Auteur')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('title')
                    ->label('Titel')
                    ->required()
                    ->minLength(3)
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get('slug') ?? '') !== Str::slug((string) $old)) { // enkel updaten als slug nog automatisch was
                            return; // stop als gebruiker de slug al manueel aangepast heeft
                        }

                        $set('slug', Str::slug((string) $state)); // maak slug op basis van de nieuwe titel
                    })
                    ->placeholder('Bijv. Laravel Filament voor beginners')
                    ->helperText('Geef een duidelijke titel voor de blogpost'),

                TextInput::make('slug')
                    ->label('Slug')
                    ->maxLength(255)
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->dehydrateStateUsing(
                        fn (?string $state, Get $get): string =>
                        filled($state)
                            ? Str::slug($state) // als gebruiker iets invulde, maak daar een nette slug van
                            : Str::slug((string) $get('title'))// anders maak slug van de titel
                    )
                    ->helperText(
                        'Je mag zelf een slug invullen. Laat je dit leeg, dan wordt automatisch de titel gebruikt met koppeltekens.'
                    ),

                Textarea::make('excerpt')
                    ->label('Samenvatting')
                    ->rows(3)
                    ->helperText('Korte introtekst voor overzichtspagina’s'),

                Textarea::make('body')
                    ->label('Inhoud')
                    ->required()
                    ->rows(12)
                    ->helperText('Volledige inhoud van de blogpost'),

                Select::make('categories')
                    ->label('Categorieën')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload(),

                Toggle::make('is_published')
                    ->label('Gepubliceerd')
                    ->default(false),

                DateTimePicker::make('published_at')
                    ->label('Publicatiedatum')
                    ->visible(fn (Get $get): bool => (bool) $get('is_published')),
            ]);
    }
}
