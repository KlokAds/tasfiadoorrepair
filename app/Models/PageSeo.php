<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageSeo extends Model
{
    protected $table = 'page_seo';

    protected $fillable = ['key', 'meta_title', 'meta_desc', 'focus_keyword', 'og_image', 'canonical', 'noindex', 'updated_by'];

    protected $casts = ['noindex' => 'boolean'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('page_seo.all'));
    }

    public static function forKey(string $key): ?self
    {
        try {
            // Cache plain arrays: the cache may not unserialize model objects (cache.serializable_classes).
            $rows = Cache::rememberForever('page_seo.all', fn () => static::all()->mapWithKeys(fn ($r) => [$r->key => $r->getAttributes()])->all());

            return isset($rows[$key]) ? (new static)->newFromBuilder($rows[$key]) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
