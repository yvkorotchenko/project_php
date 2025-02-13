<?php

namespace App\Laravue\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Laravue\Models\Role;

class RoleService extends DefaultSettingService {

	public function list(array $limitPage): LengthAwarePaginator
    {
        $roleQuery = Role::query();

        if (\array_key_exists('limit', $limitPage)) {
        	$limit = $limitPage['limit'];
        } else {
        	$limit = self::ITEMS_PER_PAGE_DEFAULT;
        }

        return $roleQuery->paginate($limit);
	}

	public function create(array $role): Role
	{
	    $role = new Role([
            'name' => $role['name'],
            'name_zh' => $role['name_zh'],
            'alias' => $role['alias'],
        ]);

	    $role->save();
	    $role->refresh();

	    return $role;
    }

	public function update(array $roleData, Role $role): Role
	{
	    $role->fill([
	        'name' => $roleData['name'],
	        'name_zh' => $roleData['name_zh'],
            'alias' => $roleData['alias'],
        ]);

        $role->save();

        return $role;
    }

	public function delete(int $roleId): bool
	{
	    $role = Role::find($roleId);

	    if ($roleId === null || $role->isAdmin()) {
	        return false;
        } else {
	        return $role->delete();
        }
    }

	public function destroyList(array $roleListIds): bool
	{
        return Role::whereIn('id', $roleListIds['ids'])->delete();
    }
}
