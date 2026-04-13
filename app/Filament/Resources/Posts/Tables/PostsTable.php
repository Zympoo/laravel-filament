<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')

            ->columns([
                ImageColumn::make('featuredImage.file_path')
                    ->label('Afbeelding')
                    ->disk('public')
                    ->square(),

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('categories.name')
                    ->label('Categorieën')
                    ->badge()
                    ->separator(', ')
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Auteur')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('is_published')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state
                        ? 'Gepubliceerd'
                        : 'Draft'
                    )
                    ->color(fn (bool $state): string => $state
                        ? 'success'
                        : 'gray'
                    ),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('published_at')
                    ->label('Publicatiedatum')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Niet gepubliceerd'),

                TextColumn::make('created_at')
                    ->label('Aangemaakt')
                    ->since()
                    ->sortable(),

                TextColumn::make('deleted_at')
                    ->label('Verwijderd op')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('is_published')
                    ->label('Status')
                    ->options([
                        1 => 'Gepubliceerd',
                        0 => 'Draft',
                    ]),

                SelectFilter::make('user_id')
                    ->label('Auteur')
                    ->options(
                        User::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->searchable(),

                SelectFilter::make('categories')
                    ->label('Categorie')
                    ->relationship('categories', 'name')
                    ->searchable()
                    ->preload(),

                TrashedFilter::make(),
            ])

            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Bewerken'),

                    Action::make('publish')
                        ->label('Publiceren')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->requiresConfirmation()
                        ->authorize(fn ($record): bool => auth()->user()->can('publish', $record))
                        ->visible(fn ($record): bool => ! $record->is_published)
                        ->action(function ($record): void {
                            $record->update([
                                'is_published' => true,
                                'published_at' => $record->published_at ?? now(),
                            ]);

                            Notification::make()
                                ->title('Post gepubliceerd')
                                ->body("De post '{$record->title}' is gepubliceerd.")
                                ->success()
                                ->send();
                        }),

                    Action::make('unpublish')
                        ->label('Depubliceren')
                        ->icon(Heroicon::OutlinedXCircle)
                        ->color('gray')
                        ->requiresConfirmation()
                        ->authorize(fn ($record): bool => auth()->user()->can('unpublish', $record))
                        ->visible(fn ($record): bool => (bool) $record->is_published)
                        ->action(function ($record): void {
                            $record->update([
                                'is_published' => false,
                                'published_at' => null,
                            ]);

                            Notification::make()
                                ->title('Post gedepubliceerd')
                                ->body("De post '{$record->title}' staat nu opnieuw als draft.")
                                ->success()
                                ->send();
                        }),

                    DeleteAction::make()->label('Verwijderen'),
                    RestoreAction::make()->label('Herstellen'),
                    ForceDeleteAction::make()->label('Definitief verwijderen'),
                ])
                    ->label('Acties')
                    ->icon(Heroicon::OutlinedEllipsisVertical)
                    ->button(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('bulkPublish')
                        ->label('Publiceren')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $count = 0;

                            $records->each(function ($record) use (&$count): void {
                                if (
                                    auth()->user()->can('publish', $record) &&
                                    ! $record->is_published
                                ) {
                                    $record->update([
                                        'is_published' => true,
                                        'published_at' => $record->published_at ?? now(),
                                    ]);
                                    $count++;
                                }
                            });

                            Notification::make()
                                ->title('Bulk publicatie voltooid')
                                ->body("{$count} post(s) zijn gepubliceerd.")
                                ->success()
                                ->send();
                        }),

                    Action::make('bulkUnpublish')
                        ->label('Depubliceren')
                        ->icon(Heroicon::OutlinedXCircle)
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $count = 0;

                            $records->each(function ($record) use (&$count): void {
                                if (
                                    auth()->user()->can('unpublish', $record) &&
                                    $record->is_published
                                ) {
                                    $record->update([
                                        'is_published' => false,
                                        'published_at' => null,
                                    ]);
                                    $count++;
                                }
                            });

                            Notification::make()
                                ->title('Bulk depublicatie voltooid')
                                ->body("{$count} post(s) zijn gedepubliceerd.")
                                ->success()
                                ->send();
                        }),

                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
