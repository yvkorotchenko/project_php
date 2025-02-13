<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use App\Laravue\Models\Role;
use Illuminate\Support\Facades\DB;

class GameCategoriesPermissions extends Migration
{
    private const PERMISSIONS_LIST = [
        'create game categories',
        'update game categories',
        'delete game categories',
    ];

    public function up()
    {
        $role = Role::whereName(['Admin'])->first();

        foreach (self::PERMISSIONS_LIST as $permissionName) {
            $issetPermition = Permission::where('name', '=', $permissionName)->first();

            if(empty($issetPermition)){

                $permission = new Permission(['name' => $permissionName]);
                $permission->save();

            }

            $permissionId = (!empty($permission->id))?$permission->id:((!empty($issetPermition->id))?$issetPermition->id:false);

            if($permissionId){
                $issetRoleHasPermissions = DB::table('role_has_permissions')
                    ->where('permission_id', $permissionId)
                    ->where('role_id', $role->id)
                    ->first();

                if(empty($issetRoleHasPermissions)){

                    DB::table('role_has_permissions')
                    ->insert(
                        [
                            'role_id' => $role->id,
                            'permission_id' => $permissionId
                        ]
                    );
                }
            }
        }
    }

    public function down()
    {
        $permisions = Permission::whereIn('name', self::PERMISSIONS_LIST);
        foreach ($permisions as $permission) {
            foreach ($permission->roles() as $role) {
                $role->revokePermissionTo($permission);
            }

            $permission->delete();
        }
    }
}
