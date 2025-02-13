<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Laravue\Models\Role;
use App\Laravue\Models\Permission;

class CreateRoutePermitionAndViewPermition extends Migration
{
    private const permissions = [
        'view menu subordinate binding log',
        'view menu sportbook management',
        'view menu customer service',
        'view menu feedback',
        'view menu stop service operation',
        'view menu stop service announcement',
        'view menu game management',
        'view menu bet amount setting',
        'view menu sign in configuration',
        'view menu announcement configuration',
        'view menu game configuration',
        'view menu customer service setting',
        'view menu gateway configuration',
        'view menu picture configuration',
        'view menu test gateway configuration',
        'view menu push activity',
        'view menu sms platform switch',
        'view menu newbie mail',
        'view menu mass mailing list',
        'view menu function management',
        'view menu newbie mail',
        'view menu rebate management',
        'view menu withdrawal management manual create withdraw order',
        'view menu withdrawal management',
        'view menu recharge management vip recharge query',
        'view menu recharge management vip recharge',
        'view menu recharge management',
        'view menu payment management',
        'view menu vip withdrawal query',
        'view menu recharge management',
        'view menu recharge blacklist',
        'view menu gm plus and minus mon',
        'view menu payment client configuration',
        'view menu gm add and subtract money record',
        'view menu online recharge query',
        'view menu member information modification',
        'view menu recharge blacklist',
        'view menu gm plus and minus mon',
        'view menu payment client configuration',
        'view menu replenishment order management',
        'view menu order replenishment record',
        'view menu level management',
        'view menu user level configuration',
        'view menu layered player inform',
        'view menu digital currency management',
        'view menu currency payment configuration',
        'view menu currency withdrawal configuration',
        'view menu vip recharge management',
        'view menu vip recharge',
        'view menu vip recharge query',
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
