<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    public const TYPES = ['fasilitas' => 'Fasilitas', 'aksesoris' => 'Aksesoris'];

    protected $fillable = ['type', 'title', 'description', 'image', 'sort_order'];
}
