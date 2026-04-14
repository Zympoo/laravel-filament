<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalPosts = Post::query()->count();

        $publishedPosts = Post::query()
            ->where('is_published', true)
            ->count();

        $draftPosts = Post::query()
            ->where('is_published', false)
            ->count();

        $totalCategories = Category::query()->count();

        return [
            Stat::make('Totaal posts', $totalPosts)
                ->description('Alle posts in het systeem')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary'),

            Stat::make('Gepubliceerd', $publishedPosts)
                ->description('Posts die live staan')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Drafts', $draftPosts)
                ->description('Posts die nog niet live staan')
                ->descriptionIcon('heroicon-o-pencil-square')
                ->color('warning'),

            Stat::make('Categorieën', $totalCategories)
                ->description('Beschikbare categorieën')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color('info'),
        ];
    }
}
