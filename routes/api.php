<?php

use App\Chat\Http\Controllers\QuestionTypeController;
use App\Http\Resources\UserResource;
use App\Laravue\Http\Controllers\FinanceController;
use App\Laravue\Http\Controllers\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Laravue\Acl;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\OperationLogController;
use App\Chat\Http\Controllers\ChatHistoryController;
use App\Chat\Http\Controllers\ClientsController;
use App\Chat\Http\Controllers\CustomerServiceController;
use App\Chat\Http\Controllers\MediaController;
use App\Chat\Http\Controllers\OperatorsController;
use App\Laravue\Http\Controllers\CustomerServiceOperatorController;
use App\Laravue\Http\Controllers\GameCategoryController;
use App\Laravue\Http\Controllers\PlatformController;
use App\Laravue\Http\Controllers\MemberInformationModificationController;
use App\Laravue\Http\Controllers\SignInController;
use App\Laravue\Http\Controllers\Google2faAuthenticationVerificationController;
use App\Laravue\Http\Controllers\DeviceNumberChangeController;
use App\Laravue\Http\Controllers\UserInformationController;
use App\Laravue\Http\Controllers\LeaguesController;
use App\Laravue\Http\Controllers\PlatformsGamesController;
use App\Laravue\Http\Controllers\AccountSuspensionController;
use App\Laravue\Http\Controllers\SportBookManagementController;
use App\Laravue\Http\Controllers\WinWithdrawMessageController;
use App\Laravue\Http\Controllers\VipRechargeController;
use App\Laravue\Http\Controllers\VerificationCodeQueryController;
use App\Laravue\Http\Controllers\SystemTimingController;
use App\Laravue\Http\Controllers\ShareAddressController;
use App\Laravue\Http\Controllers\BannerController;
use App\Laravue\Http\Controllers\TaskManagementController;
use App\Laravue\Http\Controllers\FeedbackController;
use App\Laravue\Http\Controllers\RewardsManagementController;
use App\Laravue\Http\Controllers\SevenDaysWelfareController;
use App\Laravue\Http\Controllers\IPQueryController;
use App\Laravue\Http\Controllers\UserLoginLogController;
use App\Laravue\Http\Controllers\IPWhitelistController;
use App\Laravue\Http\Controllers\ClientIPManagementController;
use App\Laravue\Http\Controllers\SensetiveOperationLogController;
use App\Laravue\Http\Controllers\AllPlayersQueryController;
use App\Laravue\Http\Controllers\ImportantNoticeController;
use App\Laravue\Http\Controllers\NewbieMailController;
use App\Laravue\Http\Controllers\ActionLogsController;
use App\Laravue\Http\Controllers\StopServiceAnnouncementController;
use App\Laravue\Http\Controllers\MailingListController;
use App\Laravue\Http\Controllers\TestIpWhitelistController;
use App\Laravue\Http\Controllers\MassMailingListController;
use App\Laravue\Http\Controllers\PlayersOnlineStatisticsController;
use App\Laravue\Http\Controllers\ForbiddenIpLoginController;
use App\Laravue\Http\Controllers\AvatarsController;
use App\Laravue\Http\Controllers\DashboardController;
use App\Laravue\Http\Controllers\RegistrationOnlineStatisticsController;

Route::post('auth/login', [AuthController::class, 'login'])
    ->middleware('validation_ip')
    ->middleware('operation_log:AuthController:login,验证：登入');

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::get('auth/user', [AuthController::class, 'user'])
        ->middleware('operation_log:Authentication:user,验证：用户');

    Route::post('auth/logout', [AuthController::class, 'logout'])
        ->middleware('operation_log:AuthController:logout,验证：登出');

    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });
    // Operation log
    Route::apiResource('operation-log', OperationLogController::class)
        ->middleware('operation_log:ListOperationLog,列出操作日志');

    // Api resource routes
    Route::apiResource('roles', RoleController::class)
        ->middleware('permission:' . Acl::PERMISSION_ROLE_MANAGE)
        ->middleware('operation_log:RoleResource,角色资源');
    /* <-- START USERS  */
    Route::get('users', [UserController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_USER_MANAGE)
        ->middleware('operation_log:ListUsers,列出用户');

    Route::post('users', [UserController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_USER_MANAGE)
        ->middleware('operation_log:Create New User,创建新用户');

    // UPDATE
    Route::put('users/{user}', [UserController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_USER_MANAGE)
        ->middleware('operation_log:Update User,更新用户');

    // DELETE
    Route::delete('users/{id}', [UserController::class, 'destroy'])
        ->middleware('permission:' . Acl::PERMISSION_USER_MANAGE)
        ->middleware('operation_log:Delete User,删除用户');

    // GET USER PERMISSIONS
    Route::get('users/{user}/permissions', [UserController::class, 'permissions'])
        ->middleware('permission:' . Acl::PERMISSION_USER_MANAGE)
        ->middleware('operation_log:All Permissions user,所有权限用户');

    // UPDATE PERMISSIONS
    Route::put('users/{user}/permissions', [UserController::class, 'updatePermissions'])
        ->middleware('permission:' . Acl::PERMISSION_USER_MANAGE)
        ->middleware('operation_log:Update permissions for User,更新用户权限');

    // UPDATE ROLE
    Route::put('users/{user}/roles', [UserController::class, 'updateRoles'])
        ->middleware('permission:' . Acl::PERMISSION_USER_MANAGE)
        ->middleware('operation_log:Update role for user,更新用户角色');
    // UPDATE PERMISSION FOR USER
    Route::put('users/{user}/add-permissions', [UserController::class, 'addPermissions'])
        ->middleware('permission:' . Acl::PERMISSION_USER_MANAGE_ADD_PERMISSION)
        ->middleware('operation_log:Add or change permissions,添加或更改权限');
    // GOOGLE_2FA_AUTHENTICATION_VERIFICATION
    Route::put('users-google-verification-code/{user}', [Google2faAuthenticationVerificationController::class, 'getQrCodeForUser'])
        ->middleware('permission:' . Acl::PERMISSION_USERS_GOOGLE_VERIFICATION_CODE)
        ->middleware('operation_log:Get google 2fa authentication verification,获取 google 2fa 身份验证验证');
    // Device change id
    Route::put('change-device-number/{user}', [DeviceNumberChangeController::class, 'deviceNumberChange'])
        ->middleware('permission:' . Acl::PERMISSION_CHANGE_DEVICE_NUMBER)
        ->middleware('operation_log:Change device number,更改设备编号');
    /* USERS END --> */
    // All permissions/all
    Route::get('permissions/all', [PermissionController::class, 'all'])
        ->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE)
        ->middleware('operation_log:List All permissions,列出所有权限');

    // Permissions
    Route::apiResource('permissions', PermissionController::class)
        ->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE)
        ->middleware('operation_log:permissions,权限');
    // sub permissions
    Route::get('permissions/{id}/children', [PermissionController::class, 'children'])
        ->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE)
        ->middleware('operation_log: sub permissions,子权限')
        ->where('id', '[0-9]+');
    // delete items list premission
    Route::post('permissions/delete-list', [PermissionController::class, 'destroyList'])
        ->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE)
        ->middleware('operation_log: permissions delete list,权限删除列表');
    // Custom routes
    // GET PERMISSIONS FOR ROLE ID
    Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])
        ->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE)
        ->middleware('operation_log:List permissions for roles,列出角色的權限');
    // delete list roles
    Route::post('roles/delete-list', [RoleController::class, 'destroyList'])
        ->middleware('permission:' . Acl::PERMISSION_ROLE_MANAGE)
        ->middleware('operation_log:roles delete list,角色删除列表');
    // member information modification
    Route::get('member/{id}', [MemberInformationModificationController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_MEMBER)
        ->middleware('operation_log:get member information modification,获取会员信息修改');
    // member information modification update
    Route::put('member/{id}', [MemberInformationModificationController::class, 'updateMemberInfo'])
        ->middleware('permission:' . Acl::PERMISSION_UPDATE_MEMBER)
        ->middleware('operation_log:member update,会员更新');
    // member full information modification
    Route::get('member-info', [MemberInformationModificationController::class, 'info'])
        ->middleware('permission:' . Acl::PERMISSION_GET_MEMBER_INFO)
        ->middleware('operation_log:member-info list,会员信息列表');
    Route::get('member/info/{fieldName}/{fieldValue}', [MemberInformationModificationController::class, 'infoPlayer'])
        ->middleware('permission:' . Acl::PERMISSION_GET_MEMBER_INFO)
        ->middleware('operation_log:member-info get by fieldname,会员信息按字段名获取');
    Route::post('member/info/remark', [MemberInformationModificationController::class, 'updateRemark'])
        ->middleware('permission:' . Acl::PERMISSION_GET_MEMBER_INFO)
        ->middleware('operation_log:member-info update remark,会员信息更新备注');
    Route::post('member/info/id/{id}', [MemberInformationModificationController::class, 'infoPlayer'])
        ->middleware('permission:' . Acl::PERMISSION_GET_MEMBER_INFO)
        ->middleware('operation_log:member info update,会员信息更新');
    // Action log
    Route::get('action-log', [ActionLogsController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_ACTION_LOG)
        ->middleware('operation_log:Show action log list,显示操作日志列表');
    // Get Actions list
    Route::get('actions', [ActionLogsController::class, 'actions']);
    Route::get('all-users', [ActionLogsController::class, 'users'])
        ->middleware('operation_log:get all users,获取所有用户');
    // UPDATE PERMISSIONS ROLE
    Route::put('add-permissions-role/{role}', [RoleController::class, 'addPermissions'])
        ->middleware('permission:' . Acl::PERMISSION_ADD_PERMISSIONS_ROLE)
        ->middleware('operation_log:add permission for role,添加角色权限');
    // DASHBOARD
    Route::get('dashboard/new-members', [UserInformationController::class, 'countNewMembers'])
        ->middleware('permission:' . Acl::PERMISSION_DASHBOARD_COUNT_NEW_MEMBERS)
        ->middleware('operation_log:Number of new Members,新会员人数');
    Route::get('dashboard/user-registration', [UserInformationController::class, 'userCountRegistration'])
        ->middleware('permission:' . Acl::PERMISSION_DASHBOARD_USER_COUNT_REGISTRATION)
        ->middleware('operation_log:Count users registration,统计用户注册');
    Route::get('dashboard/user-login', [UserInformationController::class, 'userCountLogin'])
        ->middleware('permission:' . Acl::PERMISSION_DASHBOARD_USER_LOGIN)
        ->middleware('operation_log:Count users login,统计用户登录');
    Route::get('dashboard/total-statistics', [DashboardController::class, 'totalStatistics'])
        ->middleware('permission:' . Acl::PERMISSION_DASHBOARD_TOTAL_STATISTICS)
        ->middleware('operation_log:Get statistic total recharge total withdrawal total profit and los,获取统计总充值总提现总盈亏');
    Route::get('dashboard/bet-amount-of-each-platform', [DashboardController::class, 'betAmountPlatform'])
        ->middleware('permission:' . Acl::PERMISSION_DASHBOARD_BET_AMOUNT_OF_EACH_PALTFORM)
        ->middleware('operation_log:Get list bet amount of each platform,获取各平台的列表投注额');
    Route::get('dashboard/recharge-withdrawal', [DashboardController::class, 'rechargeWithdrawal'])
        ->middleware('permission:' . Acl::PERMISSION_DASHBOARD_RECHARGE_WITHDRAWAL)
        ->middleware('operation_log:Get list to five day recharge and withdrawal,获取列表到五天充值和提现');
    Route::get('dashboard/bet-amount-platform-lists', [DashboardController::class, 'betAmountPlatformLists'])
        ->middleware('permission:' . Acl::PERMISSION_DASHBOARD_BET_AMOUNT_PLATFORM_LISTS)
        ->middleware('operation_log:Get list of platform company,获取平台公司列表');
    Route::get('dashboard/platform-profitLoss-player-betting/{id}', [DashboardController::class, 'platformProfitLossPlayerBetting'])
        ->middleware('permission:' . Acl::PERMISSION_DASHBOARD_PLATFORM_PROFIT_AND_LOSS_PLAYER_BETTING)
        ->middleware('operation_log:Get platform profit and loss and player betting,获取平台盈亏和玩家投注');
    // HOT MANAGEMENT
    Route::get('hot-management', [LeaguesController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_HOT_MANAGEMENT)
        ->middleware('operation_log:Show list league hot management,显示列表联赛热门管理');
    Route::get('previous-league-selection', [LeaguesController::class, 'previousLeagueSelection'])
        ->middleware('permission:' . Acl::PERMISSION_PREVIOUS_LEAGUE_SELECTION)
        ->middleware('operation_log:Get the previous league selection,获取之前的联赛选择');
    Route::put('hot-management/update/{id?}', [LeaguesController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_HOT_MANAGEMENT)
        ->middleware('operation_log:Update league hot management,更新热门联队展示管理');
    // PLATFORMS-GAMES
    Route::get('platforms-games', [PlatformsGamesController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_PLATFORMS_GAMES)
        ->middleware('operation_log:List of available games,可用游戏列表');
    Route::get('sport-book-management', [SportBookManagementController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_SPORT_BOOK_MANAGEMENT)
        ->middleware('operation_log:Get all sport book management,获取所有体育赛程管理');
    Route::put('sport-book-management', [SportBookManagementController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_SPORT_BOOK_MANAGEMENT)
        ->middleware('operation_log:Update sport book management,更新体育赛程管理');
    // WINNING\WITHDRAWAL
    Route::get('win-withdraw-message', [WinWithdrawMessageController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_WIN_WITHDRAW_MESSAGE)
        ->middleware('operation_log:Get list or win withdraw message,获取列表贏錢/提现广播消息');
    Route::post('win-withdraw-message', [WinWithdrawMessageController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_WIN_WITHDRAW_MESSAGE)
        ->middleware('operation_log:Create win withdraw message,创建贏錢/提现广播消息');
    Route::delete('win-withdraw-message/{ids}', [WinWithdrawMessageController::class, 'delete'])
        ->middleware('permission:' . Acl::PERMISSION_DELETE_WIN_WITHDRAW_MESSAGE)
        ->middleware('operation_log:Delete win withdraw message,删除贏錢/提现广播消息');
    // --> [SYSTEM TIMING]
    Route::get('system-timing', [SystemTimingController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_SYSTEM_TIMING)
        ->middleware('operation_log:Get list system timing,获取定时广播消息清单');
    Route::post('system-timing', [SystemTimingController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_SYSTEM_TIMING)
        ->middleware('operation_log:Create system timing message,创建定时广播消息');
    Route::put('system-timing/{id}', [SystemTimingController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_SYSTEM_TIMING)
        ->middleware('operation_log:Update system timing message,更新定时广播消息');
    Route::delete('system-timing/{ids}', [SystemTimingController::class, 'delete'])
        ->middleware('permission:' . Acl::PERMISSION_DELETE_SYSTEM_TIMING)
        ->middleware('operation_log:Delete system timing message,删除定时广播消息');
    // --> [BANNER MANAGEMENT]
    Route::get('banner-management', [BannerController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_BANNER_MAMAGEMENT)
        ->middleware('operation_log:Get list banners,获取横幅广告列表');
    Route::post('banner-management', [BannerController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_BANNER_MAMAGEMENT)
        ->middleware('operation_log:Create new banners,创建横幅广告列表');
    Route::put('banner-management/{id}', [BannerController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_BANNER_MAMAGEMENT)
        ->middleware('operation_log:Update new banners,更新横幅广告列表');
    Route::delete('banner-management/{ids}', [BannerController::class, 'delete'])
        ->middleware('permission:' . Acl::PERMISSION_DELETE_BANNER_MAMAGEMENT)
        ->middleware('operation_log:Delete banner,删除横幅广告列表');
    // UPLOAD IMAGE
    Route::post('banner-management-upload-images', [BannerController::class, 'uploadImage'])
        ->middleware('permission:' . Acl::PERMISSION_POST_BANNER_MAMAGEMENT_UPLOAD_IMAGES)
        ->middleware('operation_log:Upload banner,上传横幅广告');
    // --> [REWARD MANAGEMENT]
    Route::get('rewards-management', [RewardsManagementController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_REWARDS_MANAGEMENT)
        ->middleware('operation_log::Get list rewards image,获取奖励广告列表');
    Route::post('rewards-management', [RewardsManagementController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_REWARDS_MANAGEMENT)
        ->middleware('operation_log:Create new rewards management,创建奖励广告');
    Route::put('rewards-management/{id}', [RewardsManagementController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_REWARDS_MANAGEMENT)
        ->middleware('operation_log:Update new rewards,创建奖励广告');
    Route::delete('rewards-management/{ids}', [RewardsManagementController::class, 'delete'])
        ->middleware('permission:' . Acl::PERMISSION_DELETE_REWARDS_MANAGEMENT)
        ->middleware('operation_log:Delete rewards,删除奖励广告');
    // UPLOAD IMAGE
    Route::post('rewards-management-upload-images', [RewardsManagementController::class, 'uploadImage'])
        ->middleware('permission:' . Acl::PERMISSION_POST_REWARDS_MANAGEMENT_UPLOAD_IMAGES)
        ->middleware('operation_log:Upload rewards image,上传奖励广告图片');
    /** -- SEVEN DAYS WELFARE -- **/

    Route::get('seven-days-welfare', [SevenDaysWelfareController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_SEVEN_DAYS_WELFARE)
        ->middleware('operation_log:Get list seven days welfare,获取七日充清单');
    Route::post('seven-days-welfare', [SevenDaysWelfareController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_SEVEN_DAYS_WELFARE)
        ->middleware('operation_log:Create new seven days welfare,创建7日充福利');
    Route::put('seven-days-welfare/{id}', [SevenDaysWelfareController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_SEVEN_DAYS_WELFARE)
        ->middleware('operation_log:Update new seven days reward,更新7日充福利');
    Route::delete('seven-days-welfare/{ids}', [SevenDaysWelfareController::class, 'delete'])
        ->middleware('permission:' . Acl::PERMISSION_DELETE_SEVEN_DAYS_WELFARE)
        ->middleware('operation_log:Delete seven days welfare,删除7日充福利');
    // UPLOAD IMAGE FOR SEVEN DAYS WELFARE
    Route::post('seven-days-welfare-upload-images', [SevenDaysWelfareController::class, 'uploadImage'])
        ->middleware('permission:' . Acl::PERMISSION_POST_SEVEN_DAYS_WELFARE_UPLOAD_IMAGES)
        ->middleware('operation_log:Upload seven days reward image,上传7日充福利图片');
    //- IMPORTANT NOTICE
    Route::get('important-notice', [ImportantNoticeController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_IMPORTANT_NOTICE)
        ->middleware('operation_log:important notice list,重要通知列表');
    Route::post('important-notice', [ImportantNoticeController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_IMPORTANT_NOTICE)
        ->middleware('operation_log:important notice store,保存重要通知');
    Route::put('important-notice/{id}', [ImportantNoticeController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_IMPORTANT_NOTICE)
        ->middleware('operation_log:important notice update,重要通知更新');
    Route::delete('important-notice', [ImportantNoticeController::class, 'delete'])
        ->middleware('permission:' . Acl::PERMISSION_DELETE_IMPORTANT_NOTICE)
        ->middleware('operation_log:important notice delete,重要通知删除');
    // Test IP white list
    Route::put('test-ip-white-list/{user}', [TestIpWhitelistController::class, 'verification'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_TEST_IP_WHITE_LIST)
        ->middleware('operation_log:test ip white list verification,测试ip白名单验证');
    // ForbiddenIpLogin
    Route::put('for-bidden-ip-login/{user}', [ForbiddenIpLoginController::class, 'verification'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_FOR_BIDDEN_IP_LOGIN)
        ->middleware('operation_log:for bidden ip login delete,删除绑定ip');
    // --> [AVATAR MANAGEMENT]
    Route::get('element-ui-avatar', [AvatarsController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_ELEMENT_UI_AVATAR)
        ->middleware('operation_log:element ui avatar list,头像列表');
    Route::post('element-ui-avatar', [AvatarsController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_ELEMENT_UI_AVATAR)
        ->middleware('operation_log:element ui avatar store,头像设定保存');
    Route::put('element-ui-avatar/{id}', [AvatarsController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_ELEMENT_UI_AVATAR)
        ->middleware('operation_log:element ui avatar update,头像设定更新');
    Route::delete('element-ui-avatar/{ids}', [AvatarsController::class, 'delete'])
        ->middleware('permission:' . Acl::PERMISSION_DELETE_ELEMENT_UI_AVATAR)
        ->middleware('operation_log:element ui avatar delete,头像设定删除');
    // upload avatars
    Route::post('user-avatar-images', [AvatarsController::class, 'uploadImages'])
        ->middleware('permission:' . Acl::PERMISSION_POST_USER_AVATAR_IMAGES)
        ->middleware('operation_log:user avatar images upload images,用户头像图片上传');
});
// <--# END SYSTEM-MANAGEMENT ROUTE #-->
// <-- GAME MANAGEMENT -->
Route::get('sing-in-gold', [SignInController::class, 'index'])
    ->middleware('permission:' . Acl::PERMISSION_GET_SIGN_IN_GOLD)
    ->middleware('operation_log:sing in gold list,登入奖金清单');
Route::put('sing-in-gold/{id}', [SignInController::class, 'update'])
    ->middleware('permission:' . Acl::PERMISSION_PUT_SIGN_IN_GOLD)
    ->middleware('operation_log:sing in gold update,登入奖金更新');
/* Platforms */
Route::group(['prefix' => 'platforms'], function () {
    Route::get('', [PlatformController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_LIST_PLATFORMS)
        ->middleware('operation_log:platforms list,平台列表')
        ->name('platforms.index');

    Route::get('/{id}', [PlatformController::class, 'show'])
        ->where('id', '[0-9]+')
        ->middleware('permission:' . Acl::PERMISSION_GET_PLATFORMS)
        ->middleware('operation_log:platforms show,平台展示')
        ->name('platforms.show');

    Route::post('', [PlatformController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_PLATFORMS)
        ->middleware('operation_log:platforms store,平台储存')
        ->name('platforms.store');

    Route::put('/{id}', [PlatformController::class, 'update'])
        ->where('id', '[0-9]+')
        ->middleware('permission:' . Acl::PERMISSION_PUT_PLATFORMS)
        ->middleware('operation_log:platforms update,平台更新')
        ->name('platforms.update');

    Route::delete('/{id}', [PlatformController::class, 'destroy'])
        ->where('id', '[0-9]+')
        ->middleware('permission:' . Acl::PERMISSION_DELETE_PLATFORMS)
        ->middleware('operation_log:platforms destroy,平台删除')
        ->name('platforms.destroy');

    Route::get('/{id}/game-categories', [PlatformController::class, 'gameCategories'])
        ->where('id', '[0-9]+')
        ->middleware('permission:' . Acl::PERMISSION_GET_LIST_PLATFORMS_CATEGORIES)
        ->middleware('operation_log:platforms destroy,平台删除')
        ->name('platforms.game-categories');

    Route::put('/{id}/game-categories', [PlatformController::class, 'updateCategories'])
        ->where('id', '[0-9]+')
        ->middleware('permission:' . Acl::PERMISSION_PUT_PLATFORMS_CATEGORIES)
        ->middleware('operation_log:platforms game categories update categories,平台游戏类别更新')
        ->name('platforms.gate-categories.update');
});

/* Game categories */
Route::group(['prefix' => 'game-categories'], function () {
    Route::get('', [GameCategoryController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_GAME_CATEGORIES)
        ->middleware('operation_log:game categories list,平台游戏类别更新')
        ->name('game-categories.index');

    Route::post('', [GameCategoryController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_GAME_CATEGORIES)
        ->middleware('operation_log:game categories store,游戏类别储存')
        ->name('game-categories.store');

    Route::put('/{id}', [GameCategoryController::class, 'update'])
        ->where('id', '[0-9]+')
        ->middleware('permission:' . Acl::PERMISSION_PUT_GAME_CATEGORIES)
        ->middleware('operation_log:game categories update,游戏分类更新')
        ->name('game-categories.updatge');

    Route::delete('/{id}', [GameCategoryController::class, 'destroy'])
        ->where('id', '[0-9]+')
        ->middleware('permission:' . Acl::PERMISSION_DELETE_GAME_CATEGORIES)
        ->middleware('operation_log:game categories destroy,游戏类别删除')
        ->name('game-categories.destroy');
});

/* Games */
Route::group(['prefix' => 'games'], function () {
    Route::get('', [GameController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_GAMES)
        ->middleware('operation_log:games list,游戏列表')
        ->name('games.index');

    Route::post('', [GameController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_GAMES)
        ->middleware('operation_log:games store,游戏储存')
        ->name('games.store');

    Route::put('/{id}', [GameController::class, 'update'])
        ->where('id', '[0-9]+')
        ->middleware('permission:' . Acl::PERMISSION_PUT_GAMES)
        ->middleware('operation_log:games update,游戏更新')
        ->name('games.update');

    Route::delete('/{id}', [GameController::class, 'destroy'])
        ->where('id', '[0-9]+')
        ->middleware('permission:' . Acl::PERMISSION_DELETE_GAMES)
        ->middleware('operation_log:games destroy,游戏删除')
        ->name('games.destroy');

    Route::post('/{gameId}/upload-image', [GameController::class, 'uploadImage'])
        ->where('gameId', '[0-9]+')
        ->middleware('permission:' . Acl::PERMISSION_POST_GAMES)
        ->middleware('operation_log:games upload image,游戏图片上传')
        ->name('games.upload-image');
});

/* Chat */
Route::group(['prefix' => 'chat'], function () {
    /* Chat operators */
    Route::group(['prefix' => 'operators'], function () {
        Route::post('auth', [OperatorsController::class, 'login'])
            ->middleware('operation_log:chat operators auth login,聊天客服人员身份验证登录');
        Route::post('auth/logout', [OperatorsController::class, 'logout'])
            ->middleware('operation_log:chat operators auth logout,聊天客服人员身份验证注销');
        Route::get('/info/for-client', [OperatorsController::class, 'operatorInfo']);
    });

    /* Chat media */
    Route::post('upload-media', [MediaController::class, 'uploadMedia'])
        ->middleware('operation_log:chat upload media,聊天上传媒体');

    /* Chat Clients */
    Route::post('clients/pk', [ClientsController::class, 'clientPublicKey'])
        ->middleware('operation_log:chat clients pk,聊天客户端pk');
    Route::get('clients/in-queue', [ClientsController::class, 'clientsInQueue'])
        ->middleware('operation_log:chat clients in queue,聊天客户等候队列');
    Route::get('clients/info/{clientId}', [ClientsController::class, 'clientInfo'])
        ->middleware('operation_log:chat clients info,聊天客户信息');
    Route::get('clients/info/{clientId}/detailed', [ClientsController::class, 'clientDetailedInfo'])
        ->middleware('operation_log:chat clients info detailed,聊天客户详细信息');

    /* Chat History */
    Route::get('/history', [ChatHistoryController::class, 'list'])
        ->middleware('operation_log:chat history list,聊天记录列表');

    Route::get('/history/{id}', [ChatHistoryController::class, 'view'])
        ->where('id', '[0-9]+')
        ->middleware('operation_log:chat history view,聊天记录检视');

    Route::get('/history/search', [ChatHistoryController::class, 'search'])
        ->middleware('operation_log:chat history search,聊天记录搜索');

    Route::get('history/not-completed/for-client/{id}', [ChatHistoryController::class, 'notCompletedForClient'])
        ->where('id', '[0-9]+')
        ->middleware('operation_log:chat history not completed for client,客户未完成聊天记录');

    Route::get('/history/last/for-client', [ChatHistoryController::class, 'lastChatHistory'])
        ->middleware('operation_log:chat history last for client,客户端最后的聊天记录');

    /* Customer-Services CRUD*/
    Route::get(
        '/customer-services',
        [CustomerServiceController::class, 'index']
    )
        ->middleware('can:get list CustomerServices')
        ->middleware('operation_log:chat customer services list,聊天客服列表')
        ->name('customer_services.index');

    Route::post(
        '/customer-services',
        [CustomerServiceController::class, 'store']
    )
        ->middleware('can:create CustomerServices')
        ->middleware('operation_log:chat customer services store,聊天客服储存')
        ->name('customer_services.store');

    Route::get(
        '/customer-services/{id}',
        [CustomerServiceController::class, 'show']
    )
        ->where('id', '[0-9]+')
        ->middleware('can:get CustomerServices')
        ->middleware('operation_log:chat customer services show,聊天客服列表')
        ->name('customer_services.show');

    Route::put(
        '/customer-services/{id}',
        [CustomerServiceController::class, 'update']
    )
        ->where('id', '[0-9]+')
        ->middleware('can:update CustomerServices')
        ->middleware('operation_log:chat customer services update,聊天客服更新')
        ->name('customer_services.update');

    Route::delete(
        '/customer-services/{id}',
        [CustomerServiceController::class, 'destroy']
    )
        ->where('id', '[0-9]+')
        ->middleware('can:delete CustomerServices')
        ->middleware('operation_log:chat customer services destroy,聊天客服删除')
        ->name('customer_services.destroy');

    /* Customer-Services-Operators CRUD */
    Route::get(
        '/customer-services/operators',
        [CustomerServiceOperatorController::class, 'index']
    )
        ->middleware('can:get list CustomerServices')
        ->middleware('operation_log:chat customer services operators list,聊天客服人员名单')
        ->name('customer_service_operators.index');

    Route::post(
        '/customer-services/operators',
        [CustomerServiceOperatorController::class, 'store']
    )
        ->middleware('can:create CustomerServices')
        ->middleware('operation_log:chat customer services operators store,聊天客服人员储存')
        ->name('customer_service_operators.store');

    Route::get(
        '/customer-services/operators/{id}',
        [CustomerServiceOperatorController::class, 'show']
    )
        ->where('id', '[0-9]+')
        ->middleware('can:get CustomerServiceOperators')
        ->middleware('operation_log:chat customer services operators show,聊天客服人员展示')
        ->name('customer_service_operators.show');

    Route::put(
        '/customer-services/operators/{id}',
        [CustomerServiceOperatorController::class, 'update']
    )
        ->where('id', '[0-9]+')
        ->middleware('can:update CustomerServiceOperators')
        ->middleware('operation_log:chat customer services operators update,聊天客服人员更新')
        ->name('customer_service_operators.update');

    Route::delete(
        '/customer-services/operators/{id}',
        [CustomerServiceOperatorController::class, 'destroy']
    )
        ->where('id', '[0-9]+')
        ->middleware('can:delete CustomerServiceOperators')
        ->middleware('operation_log:chat customer services operators destroy,聊天客服人员删除')
        ->name('customer_service_operators.destroy');

    Route::get('/question-types', [QuestionTypeController::class, 'list']);
});
Route::group(['prefix' => 'account-suspension'], function () {
    Route::get('', [AccountSuspensionController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_ACCOUNT_SUSPENSION)
        ->middleware('operation_log:account suspension list,帐号暂停列表')
        ->name('account_suspension.index');
    Route::post('', [AccountSuspensionController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_ACCOUNT_SUSPENSION)
        ->middleware('operation_log:account suspension store,帐号暂停储存')
        ->name('account_suspension.store');
    Route::put('/{id}', [AccountSuspensionController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_ACCOUNT_SUSPENSION)
        ->middleware('operation_log:account suspension update,帐号暂停更新')
        ->name('account_suspension.update');
    Route::delete('/{id}', [AccountSuspensionController::class, 'destroy'])
        ->middleware('permission:' . Acl::PERMISSION_DELETE_ACCOUNT_SUSPENSION)
        ->middleware('operation_log:account suspension destroy,帐号暂停删除')
        ->name('account_suspension.destroy');
    Route::get('statuses', [AccountSuspensionController::class, 'status'])
        ->middleware('permission:' . Acl::PERMISSION_GET_ACCOUNT_SUSPENSION_STATUS)
        ->middleware('operation_log:account suspension status,帐号暂停状态')
        ->name('account_suspension.status');
    Route::get('reason', [AccountSuspensionController::class, 'reason'])
        ->middleware('permission:' . Acl::PERMISSION_GET_ACCOUNT_SUSPENSION_REASON)
        ->middleware('operation_log:account suspension reason,帐号暂停储存')
        ->name('account_suspension.reason');
});
Route::group(['prefix' => 'verification-code-query'], function () {
    Route::post('', [VerificationCodeQueryController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_VERIFICATION_CODE_QUERY)
        ->middleware('operation_log:verification code query store,验证码查询')
        ->name('verification_code_query.store');
});
Route::group(['prefix' => 'viprecharge'], function () {
    Route::get('', [VipRechargeController::class, 'list'])
        ->middleware('permission:' . Acl::PERMISSION_GET_VIP_RECHARGE)
        ->middleware('operation_log:viprecharge list,vip recharge列表')
        ->name('vip_recharge.list');
    Route::get('statuses', [VipRechargeController::class, 'statuses'])
        ->middleware('permission:' . Acl::PERMISSION_GET_VIP_RECHARGE)
        ->middleware('operation_log:viprecharge statuses,vip recharge状态')
        ->name('vip_recharge.list');
    Route::post('', [VipRechargeController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_VIP_RECHARGE)
        ->middleware('operation_log:viprecharge store,vip储存')
        ->name('vip_recharge.store');
});

Route::group(['prefix' => 'share-address'], function () {
    Route::get('/{id}', [ShareAddressController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_SHARE_ADDRESS)
        ->middleware('operation_log:share address list,分享地址列表')
        ->name('share_address.index');
    Route::put('', [ShareAddressController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_SHARE_ADDRESS)
        ->middleware('operation_log:share address update,分享地址更新')
        ->name('share_address.update');
    Route::post('generate-qr-code', [ShareAddressController::class, 'generateQrCode'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_SHARE_ADDRESS)
        ->middleware('operation_log:share address generate qr code,分享地址生成二维码')
        ->name('share_address.generate_qr_code');
});

Route::group(['prefix' => 'task-management'], function () {
    Route::post('/image-upload', [TaskManagementController::class, 'imageUpload'])
        ->middleware('permission:' . Acl::PERMISSION_POST_TASK_MANAGEMENT_IMAGE_UPLOAD)
        ->middleware('operation_log:task management image upload,任务管理图片上传')
        ->name('task_management.store');
    Route::get('', [TaskManagementController::class, 'list'])
        ->middleware('permission:' . Acl::PERMISSION_GET_TASK_MANAGEMENT_LIST)
        ->middleware('operation_log:task management list,任务管理列表')
        ->name('task_management.list');
    Route::post('', [TaskManagementController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_TASK_MANAGEMENT_STORE)
        ->middleware('operation_log:task management store,任务管理储存')
        ->name('task_management.list');
    Route::delete('/{id}', [TaskManagementController::class, 'destroy'])
        ->middleware('permission:' . Acl::PERMISSION_DELETE_TASK_MANAGEMENT_DESTROY)
        ->middleware('operation_log:task management destroy,任务管理删除')
        ->name('task_management.list');
    Route::put('/{id}', [TaskManagementController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_TASK_MANAGEMENT_UPDATE)
        ->middleware('operation_log:task management update,任务管理更新')
        ->name('task_management.list');
    Route::get('daily/list', [TaskManagementController::class, 'dailyList'])
        ->middleware('permission:' . Acl::PERMISSION_GET_TASK_MANAGEMENT_DAILY_LIST)
        ->middleware('operation_log:task management daily list,任务管理每日任务清单')
        ->name('task_management.list');
    Route::get('company/list', [TaskManagementController::class, 'companyList'])
        ->middleware('permission:' . Acl::PERMISSION_GET_TASK_MANAGEMENT_COMPANY_LIST)
        ->middleware('operation_log:task management company list,任务管理公司清单')
        ->name('task_management.list');
    Route::get('item/list', [TaskManagementController::class, 'itemList'])
        ->middleware('permission:' . Acl::PERMISSION_GET_TASK_MANAGEMENT_ITEM_LIST)
        ->middleware('operation_log:task management item list,任务管理项目列表')
        ->name('task_management.list');
    Route::get('statistic/record', [TaskManagementController::class, 'getRecord'])
        ->middleware('permission:' . Acl::PERMISSION_GET_TASK_MANAGEMENT_GET_RECORD)
        ->middleware('operation_log:task management statistic record,任务管理统计记录')
        ->name('task_management.getRecord');
    Route::get('statistic/details', [TaskManagementController::class, 'getDetails'])
        ->middleware('permission:' . Acl::PERMISSION_GET_TASK_MANAGEMENT_GET_DETAILS)
        ->middleware('operation_log:task management item statistic/details,任务管理项目统计/明细')
        ->name('task_management.getDetails');
});
Route::group(['prefix' => 'feedback'], function () {
    Route::get('', [FeedbackController::class, 'list'])
        ->middleware('permission:' . Acl::PERMISSION_GET_FEEDBACK_LIST)
        ->middleware('operation_log:feedback list,用户反馈清单')
        ->name('feedback.list');
    Route::get('/operator', [FeedbackController::class, 'getOperator'])
        ->middleware('permission:' . Acl::PERMISSION_GET_FEEDBACK_GET)
        ->middleware('operation_log:feedback operator get operator,用户反馈服务人员')
        ->name('feedback.getOperator');
    Route::get('/reply', [FeedbackController::class, 'updateReply'])
        ->middleware('permission:' . Acl::PERMISSION_GET_FEEDBACK_UPDATE)
        ->middleware('operation_log:feedback reply update reply,用户反馈更新回复')
        ->name('feedback.update');
    Route::get('/delete', [FeedbackController::class, 'destroy'])
        ->middleware('permission:' . Acl::PERMISSION_GET_FEEDBACK_DELETE)
        ->middleware('operation_log:feedback delete,用户反馈删除')
        ->name('feedback.destroy');
});
Route::group(['prefix' => 'ipquery'], function () {
    Route::get('', [IPQueryController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_IP_QUERY)
        ->middleware('operation_log:ipquery list,查询列表')
        ->name('ip_query.index');
});
Route::group(['prefix' => 'userloginlog'], function () {
    Route::get('', [UserLoginLogController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_USER_LOGIN_LOG)
        ->middleware('operation_log:user login log list,用户登录日志列表')
        ->name('user_login_log.index');
});
Route::group(['prefix' => 'ipwhitelist'], function () {
    Route::get('', [IPWhitelistController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_IP_WHITELIST)
        ->middleware('operation_log:ipwhitelist list,ip白名单')
        ->name('ip_white_list.index');
    Route::post('', [IPWhitelistController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_IP_WHITELIST)
        ->middleware('operation_log:ipwhitelist store,ip白名单储存')
        ->name('ip_white_list.store');
    Route::delete('/{id}', [IPWhitelistController::class, 'destroy'])
        ->middleware('permission:' . Acl::PERMISSION_DELETE_IP_WHITELIST)
        ->middleware('operation_log:ipwhitelist destroy,ip白名单删除')
        ->name('ip_white_list.destroy');
});
Route::group(['prefix' => 'clientipmanagement'], function () {
    Route::get('', [ClientIPManagementController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_CLIENT_IP_MANAGEMENT)
        ->middleware('operation_log:clientipmanagement list,终端管理列表')
        ->name('client_ip_management.index');
    Route::put('/{id}', [ClientIPManagementController::class, 'update'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_CLIENT_IP_MANAGEMENT)
        ->middleware('operation_log:clientipmanagement update,终端管理更新')
        ->name('client_ip_management.update');
});
Route::group(['prefix' => 'sensetiveoperationlog'], function () {
    Route::get('', [SensetiveOperationLogController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_SENSETIVE_OPERATION_LOG)
        ->middleware('operation_log:sensetiveoperationlog list,敏感操作日志列表')
        ->name('sensetive_operation_log.index');
});
Route::group(['prefix' => 'allplayersquery'], function () {
    Route::get('', [AllPlayersQueryController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_ALL_PLAYER_QUERY)
        ->middleware('operation_log:allplayersquery list,所有玩家查询列表')
        ->name('all_players_query.index');
});
Route::group(['prefix' => 'users-channel'], function () {
    Route::get('', [AllPlayersQueryController::class, 'channel'])
        ->middleware('permission:' . Acl::PERMISSION_GET_USERS_CHANNEL)
        ->middleware('operation_log:users channel channel,用户来源渠道')
        ->name('all_players_query.channel');
});
Route::group(['prefix' => 'users-vip-level'], function () {
    Route::get('', [AllPlayersQueryController::class, 'vipLevel'])
        ->middleware('permission:' . Acl::PERMISSION_GET_USERS_VIP_LEVEL)
        ->middleware('operation_log:users vip level,用户vip等级')
        ->name('all_players_query.vipLevel');
});
Route::group(['prefix' => 'newbie-mail'], function () {
    Route::post('', [NewbieMailController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_POST_NEWBIE_MAIL)
        ->middleware('operation_log:newbie-mail store,新手邮件储存')
        ->name('newbie_mail.store');
});
Route::group(['prefix' => 'finance'], function () {
    Route::get('/currencies', [FinanceController::class, 'currencyList'])
        ->middleware('permission:' . Acl::PERMISSION_GET_FINANCE_CURRENCIES)
        ->middleware('operation_log:finance currencies list,出/入金货币清单');
    Route::get('/currencies/{id}/withdraw', [FinanceController::class, 'merchantWithdraw'])
        ->where('id', '[0-9]+')
        ->middleware('permission:' . Acl::PERMISSION_GET_FINANCE_CURRENCIES_WITHDRAW)
        ->middleware('operation_log:finance currencies merchant withdraw,出/入金商户提现');
    Route::get('/user-info/{uid}', [FinanceController::class, 'userInfo'])
        ->where('uid', '[0-9]+')
        ->middleware('permission:' . Acl::PERMISSION_GET_FINANCE_USER_INFO)
        ->middleware('operation_log:finance user info,出/入金用户信息');
    Route::post('/withdraw', [FinanceController::class, 'createWithdraw'])
        ->middleware('permission:' . Acl::PERMISSION_POST_FINANCE_WITHDRAW)
        ->middleware('operation_log:finance withdraw createWithdraw,出/入金提现订单');
    Route::get('/withdraw', [FinanceController::class, 'withdrawList'])
        ->middleware('permission:' . Acl::PERMISSION_GET_FINANCE_WITHDRAW)
        ->middleware('operation_log:finance withdraw list,出/入金提现清单');
    Route::put('/withdraw/change-status', [FinanceController::class, 'changeWithdrawStatus'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_FINANCE_WITHDRAW_CHANGE_STATUS)
        ->middleware('operation_log:finance withdraw change-status,出/入金退回订单更改状态');
    Route::get('/withdraw/states', [FinanceController::class, 'withdrawStatesList'])
        ->middleware('permission:' . Acl::PERMISSION_GET_FINANCE_WITHDRAW_STATES)
        ->middleware('operation_log:finance withdraw status list,出/入金提现状态清单');
});
Route::group(['prefix' => 'stop-service-announcement'], function () {
    Route::put('{user}', [StopServiceAnnouncementController::class, 'store'])
        ->middleware('permission:' . Acl::PERMISSION_PUT_STOP_SERVICE_ANNOUNCEMENT)
        ->middleware('operation_log:stop service announcement store,停止服务公告储存')
        ->name('stop_service_announcement.store');
});
Route::get('get-all-operators', [CustomerServiceOperatorController::class, 'getAllOperators'])
    ->middleware('permission:' . Acl::PERMISSION_GET_GET_ALL_OPERATOR)
    ->middleware('operation_log:get all operators,获取所有运营商')
    ->name('customer_service_operator.getAllOperators');
Route::group(['prefix' => 'mailing-list'], function () {
    Route::get('', [MailingListController::class, 'index'])
        ->middleware('permission:' . Acl::PERMISSION_GET_MAILING_LIST)
        ->middleware('operation_log:mailing list list,邮寄名单列表')
        ->name('mailing_list.get.index');
    Route::delete('/{id}', [MailingListController::class, 'destroy'])
        ->middleware('permission:' . Acl::PERMISSION_DELETE_MAILING_LIST)
        ->middleware('operation_log:mailing list destroy,邮件列表删除')
        ->name('mailing_list.delete.destroy');
    Route::post('', [MailingListController::class, 'create'])
        ->middleware('permission:' . Acl::PERMISSION_POST_MAILING_LIST)
        ->middleware('operation_log:mailing list create,邮件列表创建')
        ->name('mailing_list.create');
});
Route::group(['prefix' => 'mass-mailing-list'], function () {
    Route::post('', [MassMailingListController::class, 'create'])
        ->middleware('permission:' . Acl::PERMISSION_POST_MASS_MAILING_LIST)
        ->middleware('operation_log:mass mailing list create,群发邮件列表创建')
        ->name('mass_mailing_list.create');
});
Route::group(['prefix' => 'statistics'], function () {
    Route::group(['prefix' => 'players-online'], function () {
        Route::get('', [PlayersOnlineStatisticsController::class, 'list'])
            ->middleware('permission:' . Acl::PERMISSION_GET_PLAYER_ONLINE_STATISTICS)
            ->middleware('operation_log:statistics players-online list,统计-在线名单');
        Route::get('/latest', [PlayersOnlineStatisticsController::class, 'latestList'])
            ->middleware('permission:' . Acl::PERMISSION_GET_PLAYER_ONLINE_STATISTICS_LATEST)
            ->middleware('operation_log:statistics latest list,统计-最新名单');
        Route::get('/count-by-devices', [PlayersOnlineStatisticsController::class, 'playersCountByDevices'])
            ->middleware('permission:' . Acl::PERMISSION_GET_PLAYER_ONLINE_STATISTICS_COUNT_BY_DEVICES)
            ->middleware('operation_log:statistics count-by-devices,依设备统计');
        Route::get('/user-info-in-game/{id}', [PlayersOnlineStatisticsController::class, 'playersInfo'])
            ->where('id', '[0-9]+')
            ->middleware('permission:' . Acl::PERMISSION_GET_PLAYER_ONLINE_STATISTICS_USER_INFO_IN_GAME)
            ->middleware('operation_log:statistics user-info-in-game,统计用户游戏信息');
    });

    Route::group(['prefix' => 'registration'], function () {
        Route::get('', [RegistrationOnlineStatisticsController::class, 'list'])
            ->middleware('operation_log:registration list,注册清单');
        Route::get('/latest', [RegistrationOnlineStatisticsController::class, 'latestList'])
            ->middleware('operation_log:registration latest,最新注册');
    });
});
