<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Postgegevens')
                    ->schema([
                        Select::make('user_id')
                            ->label('Auteur')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(Auth::id()),

                        TextInput::make('title')
                            ->label('Titel')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                                if (($get('slug') ?? '') !== Str::slug((string) $old)) {
                                    return;
                                }

                                $set('slug', Str::slug((string) $state));
                            })
                            ->placeholder('Bijv. Laravel Filament geavanceerd uitgelegd')
                            ->helperText('Geef een duidelijke titel voor de blogpost'),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->maxLength(255)
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->dehydrateStateUsing(
                                fn (?string $state, Get $get): string =>
                                filled($state)
                                    ? Str::slug($state)
                                    : Str::slug((string) $get('title'))
                            )
                            ->helperText('Laat je dit leeg, dan wordt automatisch de titel gebruikt met koppeltekens.'),

                        Textarea::make('excerpt')
                            ->label('Samenvatting')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Korte introtekst voor overzichtspagina’s'),

                        RichEditor::make('body')
                            ->label('Inhoud')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'blockquote',
                                'redo',
                                'undo',
                            ])
                            ->helperText('Volledige inhoud van de blogpost'),

                        Select::make('categories')
                            ->label('Categorieën')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->helperText('Kies één of meerdere categorieën'),

                        Toggle::make('is_published')
                            ->label('Gepubliceerd')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, bool $state, Get $get): void {
                                if ($state && blank($get('published_at'))) {
                                    $set('published_at', now()->format('Y-m-d H:i:s'));
                                }

                                if (! $state) {
                                    $set('published_at', null);
                                }
                            }),

                        DateTimePicker::make('published_at')
                            ->label('Publicatiedatum')
                            ->visible(fn (Get $get): bool => (bool) $get('is_published'))
                            ->helperText('Kies wanneer deze post gepubliceerd is of wordt.'),
                    ])
                    ->columns(2),

                Section::make('Featured image')
                    ->relationship('featuredImage')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('Afbeelding')
                            ->image()
                            ->disk('public')
                            ->directory('posts')
                            ->visibility('public')
                            ->imageEditor()
                            ->imagePreviewHeight('250')
                            ->panelLayout('integrated')
                            ->panelAspectRatio('16:9')
                            ->openable()
                            ->downloadable()
                            ->nullable()
                            ->helperText('Upload hier de featured image van de post.'),

                        TextInput::make('alt_text')
                            ->label('Alt-tekst')
                            ->maxLength(255)
                            ->helperText('Beschrijf kort wat op de afbeelding te zien is'),

                        Textarea::make('caption')
                            ->label('Caption')
                            ->rows(2)
                            ->helperText('Optioneel onderschrift bij de afbeelding'),

                        Hidden::make('disk')
                            ->default('public'),

                        Hidden::make('is_featured')
                            ->default(true),
                    ])
                    ->columns(1),
            ]);
    }
}
