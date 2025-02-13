<?php

use Illuminate\Database\Migrations\Migration;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use Illuminate\Support\Facades\DB;

class AddPermissions extends Migration
{

    private const permissions = [
        'post TaskManagement.imageUpload',
        'get TaskManagement.list',
        'post TaskManagement.store',
        'delete TaskManagement.destroy',
        'put TaskManagement.update',
        'get TaskManagement.dailyList',
        'get TaskManagement.companyList',
        'get TaskManagement.itemList',
        'get TaskManagement.getRecord',
        'get TaskManagement.getDetails',
        'get Feedback.list',
        'get Feedback.getOperator',
        'get Feedback.updateReply',
        'get Feedback.destroy',
        'get IPQuery',
        'get UserLoginLog',
        'get IPWhitelist',
        'post IPWhitelist',
        'delete IPWhitelist',
        'get ClientIPManagement',
        'put ClientIPManagement',
        'get SensetiveOperationLog',
        'get AllPlayersQuery.index',
        'get AllPlayersQuery.channel',
        'get AllPlayersQuery.vipLevel'
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
        // удалить permissions
        // удалить связь role_has_permissions
        foreach (self::permissions as $name) {
            Permission::where('name', $name)->delete();
        }
    }
}
