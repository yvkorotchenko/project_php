<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use App\Laravue\Models\Role;

class AddChatServiceAndChatServiceOperatorPermissions extends Migration
{
    private const PERMISSION_NAMES = [
        'get list CustomerServices',
        'create CustomerServices',
        'get CustomerServices',
        'update CustomerServices',
        'delete CustomerServices',
        'get list CustomerServiceOperators',
        'create CustomerServiceOperators',
        'get CustomerServiceOperators',
        'update CustomerServiceOperators',
        'delete CustomerServiceOperators',
    ];

    public function up()
    {

        $role = Role::whereName(['Admin'])->first();

        foreach (self::PERMISSION_NAMES as $permissionName) {

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
        $permisions = Permission::whereIn('name', self::PERMISSION_NAMES);
        foreach ($permisions as $permission) {
            foreach ($permission->roles() as $role) {
                $role->revokePermissionTo($permission);
            }

            $permission->delete();
        }
    }
}
