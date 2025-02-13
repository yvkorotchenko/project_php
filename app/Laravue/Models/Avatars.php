<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatars extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'img_url',
        'position',
        'visible',
    ];
}
