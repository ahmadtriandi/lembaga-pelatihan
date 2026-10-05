<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['type', 'title', 'image', 'video_url', 'sort_order'];

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    /** Ambil kode video dari berbagai bentuk tautan YouTube. */
    public static function youtubeId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $patterns = [
            '~youtu\.be/([\w-]{11})~i',
            '~youtube\.com/watch\?(?:.*&)?v=([\w-]{11})~i',
            '~youtube\.com/(?:embed|shorts|live|v)/([\w-]{11})~i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $m)) {
                return $m[1];
            }
        }

        return preg_match('/^[\w-]{11}$/', trim($url)) ? trim($url) : null;
    }

    public function getYoutubeIdAttribute(): ?string
    {
        return static::youtubeId($this->video_url);
    }

    /** Gambar sampul: foto yang diunggah, atau thumbnail dari YouTube. */
    public function getThumbUrlAttribute(): ?string
    {
        if ($this->isVideo()) {
            return $this->youtube_id ? "https://i.ytimg.com/vi/{$this->youtube_id}/hqdefault.jpg" : null;
        }

        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getEmbedUrlAttribute(): ?string
    {
        return $this->youtube_id
            ? "https://www.youtube-nocookie.com/embed/{$this->youtube_id}?autoplay=1&rel=0"
            : null;
    }
}
