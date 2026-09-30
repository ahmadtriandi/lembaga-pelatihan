<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /** Semua pengaturan sebagai array key => value (di-cache). */
    public static function allCached(): array
    {
        return Cache::rememberForever('site_settings', fn () => static::pluck('value', 'key')->all());
    }

    public static function get(string $key, $default = null)
    {
        return static::allCached()[$key] ?? $default;
    }

    public static function setMany(array $data): void
    {
        foreach ($data as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget('site_settings');
    }
}
