<?php

namespace App\Laravue\Models;

use App\Laravue\Acl;
use App\Laravue\Traits\DefaultGuardNameApiTrait;
use Illuminate\Database\Query\Builder;

/**
 * Class Permission
 *
 * @package App\Laravue\Models
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Laravue\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Laravue\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission allowed()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $display_name
 * @property string $route
 * @property int $icon_id
 * @property int $parent_id
 * @property string $hidden
 * @property int $sort
 * @property int $children
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereIconId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereSort($value)
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    private const DEFAULT_GUARD_NAME = 'api';

    use DefaultGuardNameApiTrait;

    /**
     * To exclude permission management from the list
     *
     * @param $query
     * @return Builder
     */
    public function scopeAllowed($query)
    {
        return $query->where('name', '!=', Acl::PERMISSION_PERMISSION_MANAGE);
    }
}
