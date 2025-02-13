<?php

namespace App\MtSports\Models;

use App\MtSports\Acl;
use Illuminate\Database\Query\Builder;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Class Permission
 *
 * @package App\Laravue\Models
 */
class Permission extends SpatiePermission
{
    public $guard_name = 'api';

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
