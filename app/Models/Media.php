<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'disk',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'alt_text',
        'caption',
        'sort_order',
        'is_featured',
    ];

    protected static function booted(): void
    {
        static::forceDeleted(function (Media $media) {
            if(blank($media->file_path)) {
                return;
            }

            $disk = $media->disk ?: 'public';

            if(Storage::disk($disk)->exists($media->file_path)) {
               Storage::disk($disk)->delete($media->file_path);
            }
        });
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
