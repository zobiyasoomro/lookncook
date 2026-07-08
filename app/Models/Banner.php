<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    // The table associated with the model (optional if it follows Laravel conventions)
    protected $table = 'banners';

    /**
     * The attributes that are mass assignable.
     * Add your new columns right here!
     */
    protected $fillable = [
        'title',
        'subtitle',      // <-- ADD THIS
        'description',   // <-- ADD THIS
        'image_path',
        'link_url',
        'type',
        'is_active',
    ];
}