<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Laravue\Models\Icons
 *
 * @property int $id
 * @property string $unicode
 * @property string $class_name
 * @property string $name
 * @property int $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Icons newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Icons newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Icons query()
 * @method static \Illuminate\Database\Eloquent\Builder|Icons whereClassName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Icons whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Icons whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Icons whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Icons whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Icons whereUnicode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Icons whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Icons extends Model
{
    protected $fillable = [
        'unicode',
        'class_name',
        'sort'
    ];
}
