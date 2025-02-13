<?php

namespace App\Laravue\Services;

use App\Laravue\Models\User;

class UserService extends DefaultSettingService
{
    public function list()
    {
    }

    public function paginated($limit, $role, $keyword)
    {
        $userQuery = User::query();

        if (!empty($role)) {
            $userQuery->whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            });
        }

        if (!empty($keyword)) {
            foreach ([] as $field) {
                $userQuery->where($field, 'LIKE', '%' . $keyword . '%');
            }
        }

        return $limit !== null ? $userQuery->paginate($limit) : $userQuery->get();
    }
}
