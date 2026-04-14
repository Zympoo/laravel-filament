<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\Widget;

class DraftPostsWidget extends Widget
{
    protected string $view = 'filament.widgets.draft-posts-widget';

    protected int | string | array $columnSpan = 'full';

    public function getDraftPosts()
    {
        return Post::query()
            ->with('user')
            ->where('is_published', false)
            ->latest()
            ->take(5)
            ->get();
    }
}
