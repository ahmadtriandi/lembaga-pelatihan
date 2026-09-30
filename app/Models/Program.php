<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    protected $fillable = [
        'category_id', 'name', 'level', 'duration', 'price', 'units',
        'qualifications', 'requirements', 'facilities', 'image', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /** Ubah teks "satu item per baris" menjadi array. */
    public function lines(string $field): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $this->{$field}))));
    }

    public function getPriceLabelAttribute(): string
    {
        return $this->price ? 'Rp ' . number_format($this->price, 0, ',', '.') : 'Hubungi admin';
    }
}
