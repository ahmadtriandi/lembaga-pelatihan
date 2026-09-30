<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    public const STATUSES = ['baru' => 'Baru', 'dihubungi' => 'Sudah dihubungi', 'terdaftar' => 'Terdaftar', 'batal' => 'Batal'];

    protected $fillable = ['program_id', 'name', 'phone', 'email', 'company', 'class_type', 'message', 'status'];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
