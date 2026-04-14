<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\Widget;

class RecentPostsWidget extends Widget
{
    protected string $view = 'filament.widgets.recent-posts-widget';

    protected int | string | array $columnSpan = 'full';

    public function getRecentPosts()
    {
        return Post::query()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();
    }
}
