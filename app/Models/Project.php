<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $appends = [
        'image_src',
    ];

    protected $fillable = [
        'title',
        'description',
        'tech_stack',
        'live_url',
        'github_url',
        'image_url',
    ];

    public function getImageUrlAttribute($value): ?string
    {
        if (!is_string($value) || $value === '') {
            return null;
        }

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        $host = parse_url($value, PHP_URL_HOST);
        $path = parse_url($value, PHP_URL_PATH);

        if (is_string($host) && in_array($host, ['localhost', '127.0.0.1'], true) && is_string($path) && $path !== '') {
            return $path;
        }

        return $value;
    }

    public function getImageSrcAttribute(): ?string
    {
        $imageUrl = $this->image_url;

        if (!is_string($imageUrl) || $imageUrl === '') {
            return null;
        }

        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return $imageUrl;
        }

        if (!str_starts_with($imageUrl, '/')) {
            $imageUrl = '/'.$imageUrl;
        }

        return url($imageUrl);
    }
}
