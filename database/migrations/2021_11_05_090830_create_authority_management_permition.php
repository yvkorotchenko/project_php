<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;

class CreateAuthorityManagementPermition extends Migration
{
    private const permissions = [
        'authority management edit',
        'authority management list delete',
        'authority management delete',
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = Role::whereName(['Admin'])->first();

        if (!empty($role)) {
            foreach(self::permissions as $name) {
                $issetPermission = Permission::where('name', '=', $name)->first();

                if (empty($issetPermission)) {
                    $permission = new Permission(['name' => $name]);
                    $permission->save();
                }

                $permissionId = (!empty($permission->id)) ? $permission->id : ((!empty($issetPermission->id)) ? $issetPermission->id : false);

                if ($permissionId) {
                    $issetRoleHasPermissions = DB::table('role_has_permissions')
                        ->where('permission_id', $permissionId)
                        ->where('role_id', $role->id)
                        ->first();

                    if (empty($issetRoleHasPermissions)) {
                        DB::table('role_has_permissions')->insert([
                            'role_id' => $role->id,
                            'permission_id' => $permissionId,
                        ]);
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
        foreach(self::permissions as $name) {
            Permission::where('name', $name)->delete();
        }
    }
}
