<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Laravue\Models\HotManagementLeague
 *
 * @property int $id
 * @property int $gameId
 * @property string $ids
 * @property int $days
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|HotManagementLeague newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HotManagementLeague newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HotManagementLeague query()
 * @method static \Illuminate\Database\Eloquent\Builder|HotManagementLeague whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HotManagementLeague whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HotManagementLeague whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HotManagementLeague whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HotManagementLeague whereIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HotManagementLeague whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HotManagementLeague extends Model
{
    protected $fillable = [
        'gameId',
        'ids',
        'days',
    ];
}
