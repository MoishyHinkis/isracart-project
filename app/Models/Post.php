<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['media'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $path = @$attributes['path'] ?: '';
                if (!$path) {
                    return null;
                }
                return [
                    'path' => Storage::url($path),
                    'type' => Storage::mimeType($path)
                ];
            },
            set: function ($value, $attributes) {

                $path = @$attributes['path'] ?: '';
                if (!$value) {
                    if (
                        Storage::exists($path)
                    ) {
                        Storage::delete($path);
                    }
                    $path = null;
                } elseif ($value instanceof UploadedFile ) {
                    $path = $value->storePublicly('public/posts');
                }
                return [
                    'path' => $path
                ];
            }
        );
    }

    public function scopeFilter($query, $filter)
    {

        return $query->when(
            Arr::has($filter, 'user_id'),
            fn ($q) => $q->where('user_id', $filter['user_id'])
        );
    }

    public static function booted()
    {

        parent::booted();
        static::deleted(function ($post) {
            if ($post->path && Storage::exists($post->path)) {
                Storage::delete($post->path);
            }
        });
    }
}
