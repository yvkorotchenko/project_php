<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Laravue\Models\ModelHasRoles
 *
 * @property int $role_id
 * @property string $model_type
 * @property int $model_id
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHasRoles newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHasRoles newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHasRoles query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHasRoles whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHasRoles whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHasRoles whereRoleId($value)
 * @mixin \Eloquent
 */
class ModelHasRoles extends Model
{
    use HasFactory;
}
