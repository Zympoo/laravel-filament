<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'is_published',
        'published_at',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Post $post): void {
            if ($post->isForceDeleting()) {
                $post->media()
                    ->withTrashed()
                    ->get()
                    ->each(fn (Media $media) => $media->forceDelete());

                return;
            }
            $post->media()
                ->get()
                ->each(fn (Media $media) => $media->delete());
        });

        static::restored(function (Post $post): void {
            $post->media()
                ->withTrashed()
                ->get()
                ->each(function (Media $media): void {
                    if ($media->trashed()) {
                        $media->restore();
                    }
                });
        });

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function featuredImage(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('is_featured', true);
    }
}
