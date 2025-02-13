<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use Illuminate\Support\Facades\DB;

class CreateUserManagementPermition extends Migration
{
    private const permissions = [
        'user management list delete',
        'user management add',
        'view menu user management',
        'user management edit',
        'user management roles',
        'user management authority',
        'user management delete',
        'user management google verify identity',
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
                $issetPermition = Permission::where('name', '=', $name)->first();

                if (empty($issetPermition)) {
                    $permission = new Permission(['name' => $name]);
                    $permission->save();
                }

                $permissionId = (!empty($permission->id)) ? $permission->id : ((!empty($issetPermition->id)) ? $issetPermition->id : false);

                if ($permissionId) {
                    $issetRoleHasPermissions = DB::table('role_has_permissions')
                        ->where('permission_id', $permissionId)
                        ->where('role_id', $role->id)
                        ->first();

                    if (empty($issetRoleHasPermissions)) {
                        DB::table('role_has_permissions')
                            ->insert([
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
        foreach (self::permissions as $name) {
            Permission::where('name', $name)->delete();
        }
    }
}
