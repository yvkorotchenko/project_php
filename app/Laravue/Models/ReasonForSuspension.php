<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Laravue\Models\ReasonForSuspension
 *
 * @property int $id
 * @property string $name_en
 * @property string $name_zh
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonForSuspension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonForSuspension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonForSuspension query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonForSuspension whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonForSuspension whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonForSuspension whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonForSuspension whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReasonForSuspension extends Model
{
    use HasFactory;
}
