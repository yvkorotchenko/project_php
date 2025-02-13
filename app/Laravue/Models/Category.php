<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Laravue\Models\Category
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @mixin \Eloquent
 */
class Category extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
    ];
}
