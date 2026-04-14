<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PostsPerMonthChart extends ChartWidget
{
    protected ?string $heading = 'Posts per maand';

    protected ?string $description = 'Overzicht van het aantal aangemaakte posts in de laatste 6 maanden.';

    protected function getData(): array
    {
        $months = collect(range(5, 0))
            ->map(function (int $monthsAgo) {
                return Carbon::now()->subMonths($monthsAgo);
            })
            ->push(Carbon::now());

        $labels = $months
            ->map(function (Carbon $month) {
                return $month->translatedFormat('M Y');
            })
            ->toArray();

        $data = $months
            ->map(function (Carbon $month) {
                return Post::query()
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            })
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Aantal posts',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
