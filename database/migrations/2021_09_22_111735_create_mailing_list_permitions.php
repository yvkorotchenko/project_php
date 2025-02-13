<?php

use Illuminate\Database\Migrations\Migration;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use Illuminate\Support\Facades\DB;

class CreateMailingListPermitions extends Migration
{

    private const permissions = [
        'get CustomerServiceOperator.getAllOperators',
        'get MailingList.index',
        'delete MailingList.destroy',
        'post MailingList.create',
    ];

/**
 * Run the migrations.
 *
 * @return void
 */
public function up()
{
    $role = Role::whereName(['Admin'])->first();
    if(!empty($role)){
        foreach (self::permissions as $name) {
            $issetPermition = Permission::where('name', '=', $name)->first();
            if(empty($issetPermition)){
                $permission = new Permission(['name' => $name]);
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
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    foreach (self::permissions as $name) {
        Permission::where('name', $name)->delete();
    }
}
}
