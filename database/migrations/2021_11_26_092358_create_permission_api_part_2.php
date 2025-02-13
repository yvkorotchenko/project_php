<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Laravue\Models\Role;
use App\Laravue\Models\Permission;

class CreatePermissionApiPart2 extends Migration
{

    private const permissions = [
        'view menu feedback',
        'view menu customer service log',
        'view menu customer service waiting list',
        'view menu forbidden ip login',
        'view menu test ip white list',
        'view menu service suspension management',
        'view menu share address',
        'view menu rewards management',
        'view menu seven days welfare',
        'view menu important notice',
        'view menu banner management',
        'view menu hot management',
        'view menu mail management',
        'view menu mailing list',
        'view menu bulk-mail generation form',
        'view menu task management',
        'view menu task management task list',
        'view menu task management task record',
        'view menu task management task details',
        'view menu third party management',
        'view menu platform settings',
        'view menu category settings',
        'view menu game list',
        'view menu ip management',
        'view menu client ip management',
        'view menu ip white list',
        'view menu share address',
        'view menu rewards management',
        'view menu seven days welfare',
        'view menu important notice',
        'view menu banner management',
        'view menu hot management',
        'view menu bank card configuration',
        'view menu customer service replenishment',
        'view menu playing online',
        'view menu real time rergistration',
        'view menu real time registration',
        'view menu payment number curve',
        'view menu automatic withdrawal information',
        'view menu bank card automatic withdrawal configuration',
        'view menu coin payments withdrawal',
        'view menu coin payments application list',
        'view menu coin payments audit list',
        'view menu bank withd raw',
        'view menu bank card application list',
        'view menu bank card review list',
        'view menu black list of withdrawals',
        'view menu alarm configuration',
        'view menu withdraw configuration',
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
                    $issetRoleHasPermissions = DB::table('role_has_permissions', $permissionId)
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
