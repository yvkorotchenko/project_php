<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Laravue\Models\Role;
use App\Laravue\Models\Permission;

class CreatePermissionApi extends Migration
{
    private const permissions = [
        'view menu zip',
        'view menu pdf',
        'view menu i18n',
        'role management',
        'add or change permissions',
        'get google 2fa authentication verification',
        'change device number',
        'get member information modification',
        'put add permissions role',
        'get action log list',
        'get hot management',
        'put hot management',
        'get previous league selection',
        'get platforms games',
        'get sport book management',
        'put sport book management',
        'get win withdraw message',
        'post win withdraw message',
        'delete win withdraw message',
        'get system timing',
        'post system timing',
        'put system timing',
        'delete system timing',
        'get banner management',
        'post banner management',
        'put banner management',
        'delete banner management',
        'post banner management upload images',
        'get rewards management',
        'post rewards management',
        'put rewards management',
        'delete rewards management',
        'post rewards management upload images',
        'get seven days welfare',
        'post seven days welfare',
        'put seven days welfare',
        'delete seven days welfare',
        'delete seven days welfare upload images',
        'get important notice',
        'post important notice',
        'put important notice',
        'delete important notice',
        'put test ip white list',
        'put for bidden ip login',
        'get element ui avatar',
        'post element ui avatar',
        'put element ui avatar',
        'delete element ui avatar',
        'post user avatar images',
        'get sing in gold',
        'put sing in gold',
        'get list platforms',
        'get finance currencies',
        'get finance currencies withdraw',
        'get finance user info',
        'post finance withdraw',
        'get finance withdraw',
        'get finance withdraw change status',
        'get finance withdraw states',
        'post StopServiceAnnouncement.store',
        'get statistics players online',
        'get statistics players online latest',
        'get statistics players online count by devices',
        'get statistics players online user info in game'
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
