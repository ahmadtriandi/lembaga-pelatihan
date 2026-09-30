<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'company', 'program', 'rating', 'content', 'photo', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
