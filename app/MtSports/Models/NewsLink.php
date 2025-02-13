<?php

namespace App\MtSports\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\MtSports\Models\NewsLink
 *
 * @property int $id
 * @property string $type
 * @property string $type_local
 * @property string|null $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLink whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLink whereUrl($value)
 * @mixin \Eloquent
 * @property string|null $type_local
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLink whereTypeLocal($value)
 */
class NewsLink extends Model
{
    use HasFactory;

    protected $connection = 'mtsports';

    public $fillable = [
        'type',
        'type_local',
        'url'
    ];
}
