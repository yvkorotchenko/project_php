<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Laravue\Models\Role;
use App\Laravue\Models\Permission;

class CreateHierarchyForPermission extends Migration
{
    private const PERMISSIONS = [
        'view element in home page' => [
            'home page user registration chart',
            'home page user login chart',
            'home page platform profit and loss and player betting',
            'home page bet amount each platform',
            'home page Recharge and withdrawal',
            'home page number new members',
            'home page total recharge',
            'home page total withdrawal',
            'home page total profit and loss',
            'get dashboard count new members',
            'get dashboard user count registration',
            'get dashboard user login',
            'get dashboard total statistics',
            'get dashboard bet amount of each platform',
            'get dashboard recharge withdrawal',
            'get dashboard bet amount platform lists',
            'get dashboard platform profit and loss player betting',
        ],
        'view menu active data' => [
        ],
        'view menu active list data' => [
        ],
        'view menu real time rergistration' => [
        ],
        'view menu playing online' => [
        ],
        'view menu game settings' => [
        ],
        'view menu account management' => [
        ],
        'view menu member information modification' => [
            'get member information modification',
            'update member information modification.updateMemberInfo',
            'get member information modification.info',
            'member information modification search',
            'member information modification modify information',
        ],
        'view menu device number change' => [
            'change device number',
        ],
        'view menu verification code query' => [
        ],
        'view menu player information' => [
        ],
        'view menu account suspension' => [
            'account suspension add',
            'account suspension search',
            'account suspension delete',
            'get AccountSuspension',
            'status AccountSuspension',
            'create AccountSuspension',
            'update AccountSuspension',
            'destroy AccountSuspension',
        ],
        'view menu ip query' => [
            'ip query search filter',
            'ip query download file Excel',
            'ip query download file CVS',
            'ip query print table',
            'get IPQuery',
        ],
        'view menu all players query' => [
            'all player query filters search',
            'all player query download file Excel',
            'all player query download file CVS',
            'all player query print table',
            'get AllPlayersQuery.index',
            'get AllPlayersQuery.channel',
            'get AllPlayersQuery.vipLevel',
        ],
        'view menu user login log' => [
            'get UserLoginLog',
        ],
        'view menu sensitive operation log' => [
            'get SensetiveOperationLog',
        ],
        'view menu marquee management' => [
        ],
        'view menu system timing' => [
            'system timing list delete',
            'system timing add',
            'system timing repeat',
            'system timing edit',
            'system timing delete',
            'system timing status',
            'get system timing',
            'post system timing',
            'put system timing',
            'delete system timing',
        ],
        'view menu winning withdrawal' => [
            'winning withdrawal list delete',
            'winning withdrawal add',
            'winning withdrawal delete',
            'get win withdraw message',
            'post win withdraw message',
            'delete win withdraw message',
        ],
        'view menu ip management' => [
        ],
        'view menu client ip management' => [
            'get ClientIPManagement',
            'put ClientIPManagement',
        ],
        'view menu ip white list' => [
            'ip whitelist add',
            'ip whitelist delete',
            'get IPWhitelist',
            'post IPWhitelist',
            'delete IPWhitelist',
        ],
        'view menu forbidden ip login' => [
            'put for bidden ip login',
        ],
        'view menu test ip white list' => [
            'put test ip white list',
        ],
        'view menu service suspension management' => [
        ],
        'view menu stop service announcement' => [
            'post StopServiceAnnouncement.store',
        ],
        'view menu game management' => [
        ],
        'view menu hot management' => [
            'hot management edit',
            'get hot management',
            'put hot management',
        ],
        'view menu share address' => [
            'get ShareAddress',
            'update ShareAddress',
        ],
        'view menu activity configuration' => [
        ],
        'view menu rewards management' => [
            'rewards management list delete',
            'rewards management add',
            'rewards management delete',
            'rewards management edit',
            'get rewards management',
            'post rewards management',
            'put rewards management',
            'delete rewards management',
            'post rewards management upload images',
        ],
        'view menu seven days welfare' => [
            'view menu seven days welfare lists delete',
            'view menu seven days welfare add',
            'view menu seven days welfare delete',
            'view menu seven days welfare edit',
            'get seven days welfare',
            'post seven days welfare',
            'put seven days welfare',
            'delete seven days welfare',
            'delete seven days welfare upload images',
        ],
        'view menu banner management' => [
            'banner management list delete',
            'banner management add',
            'banner management delete',
            'banner management edit',
            'get banner management',
            'post banner management',
            'put banner management',
            'delete banner management',
            'post banner management upload images',
        ],
        'view menu important notice' => [
            'important notice add',
            'important notice status',
            'important notice update',
            'important notice delete',
            'get important notice',
            'post important notice',
            'put important notice',
            'delete important notice',
        ],
        'view menu mail management' => [
        ],
        'view menu mailing list' => [
            'mailing list create',
            'mailing list delete',
            'get NewbieMailController.store',
            'get MailingList.index',
            'delete MailingList.destroy',
            'post MailingList.create',
            'post MassMailingList.create',
        ],
        'view menu bulk-mail generation form' => [
        ],
        'view menu third party management' => [
        ],
        'view menu platform settings' => [
            'platform settings delete',
            'platform settings save',
            'platform settings create',
            'platform settings edit',
            'platform settings categories',
            'get list platforms',
            'get platform',
            'create platforms',
            'update platforms',
            'update platform categories',
            'get list platform categories',
            'delete platforms',
        ],
        'view menu category settings' => [
            'category settings edit',
            'category settings delete',
        ],
        'view menu game list' => [
            'game list edit',
            'game list delete',
            'get list game categories',
            'create game categories',
            'update game categories',
            'delete game categories',
            'get list games',
            'create games',
            'update games',
            'delete games',
        ],
        'view menu task management' => [
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
        ],
        'view menu task management task list' => [
            'task management task list add',
            'task management task edit',
            'task management task delete',
        ],
        'view menu task management task record' => [
            'task record search filter',
        ],
        'view menu task management task details' => [
            'task management task details search filter',
        ],
        'view menu payment management' => [
        ],
        'view menu recharge management' => [
        ],
        'view menu withdrawal management' => [
        ],
        'view menu vip withdrawal query' => [
            'vip withdrawal query filter search',
            'vip withdrawal query audit',
            'vip withdrawal query paid',
            'vip withdrawal query reject',
            'get finance currencies',
            'get finance currencies withdraw',
            'get finance user info',
            'post finance withdraw',
            'get finance withdraw',
            'get finance withdraw change status',
            'get finance withdraw states',
        ],
        'view menu vip withdrawal' => [
        ],
        'view menu recharge management vip recharge' => [
            'get VipRecharge',
            'create VipRecharge',
        ],
        'view menu recharge management vip recharge query' => [
            'vip recharge query filter search',
            'vip recharge query print table',
            'vip recharge query download file Excel',
            'vip recharge query download file CVS',
            'vip recharge query print',
        ],
        'view menu real time data' => [
        ],
        'view menu system management' => [
        ],
        'view menu user management' => [
            'user management list delete',
            'user management add',
            'user management edit',
            'user management authority',
            'user management roles',
            'user management delete',
            'user management google verify identity',
            'manage user',
            'manage permission',
            'add or change permissions',
        ],
        'view menu system management role' => [
            'role management list delete',
            'role management add',
            'role management edit',
            'role management authority',
            'role management delete',
            'role management',
            'put add permissions role',
        ],
        'view menu system management operation log' => [
            'operation log select users',
            'operation log select actions',
            'operation log start data',
            'operation log end data',
            'operation log search',
        ],
        'view menu feedback' => [
            'feedback reply',
            'feedback view',
            'feedback delete',
            'get Feedback.list',
            'get Feedback.getOperator',
            'get Feedback.updateReply',
            'get Feedback.destroy',
        ],
        'view menu customer service chat system' => [
        ],
        'view menu game testing' => [
        ],
        'view menu game testing notification' => [
        ],
        'system permissions' => [
            'get google 2fa authentication verification',
            'get previous league selection',
            'get platforms games',
        ],
        'other permissions' => [
            'get sport book management',
            'put sport book management',
            'create VerificationCodeQuery',
        ],
    ];
    private const FIX = [
        'recharge management vip recharge query download file' => 'vip recharge query download file',
        'recharge management vip recharge query download file CSV' => 'vip recharge query download file CSV',
        'recharge management vip recharge query download file Excel' => 'vip recharge query download file Excel',
        'recharge management vip recharge query filter search' => 'vip recharge query filter search',
        'recharge management vip recharge query print' => 'vip recharge query print',
        'view menu seven days welfare add' => 'seven days welfare add',
        'view menu seven days welfare delete' => 'seven days welfare delete',
        'view menu seven days welfare edit' => 'seven days welfare edit',
        'view menu seven days welfare lists delete' => 'seven days welfare lists delete',
        'payment management withdrawal management vip withdrawal query audit' => 'vip withdrawal query audit',
        'payment management withdrawal management vip withdrawal query filter search' => 'vip withdrawal query filter search',
        'payment management withdrawal management vip withdrawal query paid' => 'vip withdrawal query paid',
        'payment management withdrawal management vip withdrawal query reject' => 'vip withdrawal query reject',
    ];
    private const DELETE_OLD_PERMISSIONS = [
        'manage article',
        'view menu element ui',
        'view menu permission',
        'view menu charts',
        'view menu components',
        'view menu nested routes',
        'view menu table',
        'view menu administrator',
        'view menu theme',
        'view menu clipboard',
        'view menu excel',
        'view menu i18n',
        'manage article',
        'manage system-management',
        'delete',
        'edit',
        'roles',
        'authority',
        'verify identidy',
        'add',
        'query',
        'reset',
        'select users',
        'select actions',
        'sub-permission',
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create fix method for migration
        $fix = [];

        $role = Role::whereName(['Admin'])->first();
        $parentId = 0;
        $permissionsIds = [];

        if (!empty($role)) {
            foreach (self::PERMISSIONS as $key => $val) {
                // search parent permission
                $issetPermission = Permission::where('name', '=', $key)->first();

                if (empty($issetPermission)) {
                    $permission = new Permission(['name' => $key]);
                    $permission->save();
                    $parentId = $permission->id;


                    foreach ($val as $name) {
                        // search children permission
                        $issetSubPermission = Permission::where('name', '=', $name)->first();

                        if (empty($issetSubPermission)) {
                            $subPermission = new Permission(['name' => $name, 'parent_id' => $parentId]);
                            $subPermission->save();
                            $subPermission->children = $subPermission->id;
                        } else {
                            $issetSubPermission->parent_id = $parentId;
                            $issetSubPermission->save();
                            $issetSubPermission->children = $issetSubPermission->id;
                        }
                    }
                } else {
                    // find parent permission
                    $issetPermission->parent_id = 0;
                    $parentId = $issetPermission->id;

                    // find children permission
                    foreach ($val as $name) {
                        $issetSubPermission = Permission::where('name', '=', $name)->first();

                        if (empty($issetSubPermission)) {
                            $subPermission = new Permission(['name' => $name, 'parent_id' => $parentId]);
                            $subPermission->save();
                            $subPermission->children = $subPermission->id;
                        } else {
                            $issetSubPermission->parent_id = $parentId;
                            $issetSubPermission->save();
                            $issetSubPermission->children = $issetSubPermission->id;
                        }
                    }
                }
            }
            // Fix permissions name
            foreach (self::FIX as $oldName => $newName) {
                $fixPermission = Permission::where('name', '=', $oldName)->first();

                if (!empty($fixPermission)) {
                    $updateNamePermission = Permission::where('name', '=', $newName)->first();

                    if (empty($updateNamePermission)) {
                        $fixPermission->name = $newName;
                        $fixPermission->save();
                    } else {
                        $fixPermission->delete();
                    }
                }
            }

            foreach(Permission::all() as $ids) {
                array_push($permissionsIds, $ids->id);
            }

            // set all permission for admin
            if (count($permissionsIds) > 0) {
                $role->syncPermissions($permissionsIds);
            }

            foreach (self::DELETE_OLD_PERMISSIONS as $deleteName) {
                $deleteOldPermission = Permission::where('name', '=', $deleteName)->first();

                if (!empty($deleteOldPermission)) {
                    $deleteOldPermission->delete();
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
        foreach (self::PERMISSIONS as $name) {
            Permission::where('name', $name)->delete();
        }
    }
}
