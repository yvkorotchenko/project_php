<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Laravue\Models\Role;
use App\Laravue\Models\Permission;

class CreateVuejsRoutePermition extends Migration
{
    private const permissions = [
        'view menu account management',
        'view menu verification code query',
        'view menu device number change',
        'view menu member information modification',
        'view menu player information',
        'view menu ip query',
        'view menu all players query',
        'view menu user login log',
        'view menu account suspension',
        'view menu sensitive operation log',
        'view menu customer service',
        'view menu feedback',
        'view menu marquee management',
        'view menu system timing',
        'view menu winning withdrawal',
        'view menu ip management',
        'view menu client ip management',
        'view menu ip white list',
        'view menu forbidden ip login',
        'view menu test ip white list',
        'view menu service suspension management',
        'view menu game management',
        'view menu share address',
        'view menu seven days welfare',
        'view menu seven days welfare lists delete',
        'view menu seven days welfare add',
        'view menu task management',
        'view menu task management task list',
        'view menu task management task record',
        'view menu task management task details',
        'view menu hot management',
        'view menu activity configuration',
        'view menu rewards management',
        'view menu banner management',
        'view menu important notice',
        'view menu mail management',
        'view menu newbie mail',
        'view menu mailing list',
        'view menu bulk-mail generation form',
        'view menu third party management',
        'view menu platform settings',
        'view menu category settings',
        'view menu game list',
        'view menu payment management',
        'view menu recharge management',
        'view menu recharge management vip recharge',
        'view menu recharge management vip recharge query',
        'view menu withdrawal management',
        'view menu withdrawal management manual create withdraw order',
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
