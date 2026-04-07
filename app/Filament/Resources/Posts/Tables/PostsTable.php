<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID') // toon ID-kolom
                    ->sortable(), // sorteerbaar maken

                TextColumn::make('title')
                    ->label('Titel') // toon titel
                    ->searchable() // zoekbaar maken
                    ->sortable(), // sorteerbaar maken

                TextColumn::make('slug')
                    ->label('Slug') // toon slug
                    ->searchable() // zoekbaar maken
                    ->toggleable(), // gebruiker mag kolom tonen/verbergen

                TextColumn::make('user.name')
                    ->label('Auteur') // toon naam van gerelateerde user
                    ->sortable(), // sorteerbaar maken

                IconColumn::make('is_published')
                    ->label('Live') // kort label voor publicatiestatus
                    ->boolean(), // toon als boolean-icoon

                TextColumn::make('published_at')
                    ->label('Publicatiedatum') // datum van publicatie
                    ->dateTime('d/m/Y H:i') // formaat van datum en tijd
                    ->sortable(), // sorteerbaar maken

                TextColumn::make('created_at')
                    ->label('Aangemaakt') // aanmaakdatum
                    ->since() // relatieve tijd tonen
                    ->sortable(), // sorteerbaar maken
            ])

            ->filters([
                SelectFilter::make('is_published')
                    ->label('Status') // label van filter
                    ->options([
                        1 => 'Gepubliceerd', // waarde 1
                        0 => 'Draft', // waarde 0
                    ]),
            ])

            ->recordActions([
                EditAction::make(), // edit knop per rij
                DeleteAction::make(), // delete knop per rij
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(), // bulk delete voor meerdere records
                ]),
            ]);
    }
}
