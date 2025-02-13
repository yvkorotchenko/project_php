<?php

namespace App\Laravue;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class Acl
 *
 * @package App\Laravue
 */
final class Acl
{
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_EDITOR = 'editor';
    const ROLE_USER = 'user';
    const ROLE_VISITOR = 'visitor';
    const ROLE_ROOT = 'root';
    const ROLE_DESIGNER = 'designer';
    const ROLE_OPERATOR = 'operator';

    // DASHBOARD
    const PERMISSION_DASHBOARD_COUNT_NEW_MEMBERS = 'get dashboard count new members';
    const PERMISSION_DASHBOARD_USER_COUNT_REGISTRATION = 'get dashboard user count registration';
    const PERMISSION_DASHBOARD_USER_LOGIN = 'get dashboard user login';
    const PERMISSION_DASHBOARD_TOTAL_STATISTICS = 'get dashboard total statistics';
    const PERMISSION_DASHBOARD_BET_AMOUNT_OF_EACH_PALTFORM = 'get dashboard bet amount of each platform';
    const PERMISSION_DASHBOARD_RECHARGE_WITHDRAWAL = 'get dashboard recharge withdrawal';
    const PERMISSION_DASHBOARD_BET_AMOUNT_PLATFORM_LISTS = 'get dashboard bet amount platform lists';
    const PERMISSION_DASHBOARD_PLATFORM_PROFIT_AND_LOSS_PLAYER_BETTING = 'get dashboard platform profit and loss player betting';
    const PERMISSION_USER_MANAGE = 'manage user';
    const PERMISSION_PERMISSION_MANAGE = 'manage permission';
    const PERMISSION_ROLE_MANAGE = 'role management';
    const PERMISSION_USER_MANAGE_ADD_PERMISSION = 'add or change permissions';
    const PERMISSION_USERS_GOOGLE_VERIFICATION_CODE = 'get google 2fa authentication verification';
    const PERMISSION_CHANGE_DEVICE_NUMBER = 'change device number';
    const PERMISSION_GET_MEMBER = 'get member information modification';
    const PERMISSION_UPDATE_MEMBER = 'update member information modification.updateMemberInfo';
    const PERMISSION_GET_MEMBER_INFO = 'get member information modification.info';
    const PERMISSION_ADD_PERMISSIONS_ROLE = 'put add permissions role';
    const PERMISSION_ACTION_LOG = 'get action log list';
    const PERMISSION_GET_HOT_MANAGEMENT = 'get hot management';
    const PERMISSION_PUT_HOT_MANAGEMENT = 'put hot management';
    const PERMISSION_PREVIOUS_LEAGUE_SELECTION = 'get previous league selection';
    const PERMISSION_GET_PLATFORMS_GAMES = 'get platforms games';
    const PERMISSION_GET_SPORT_BOOK_MANAGEMENT = 'get sport book management';
    const PERMISSION_PUT_SPORT_BOOK_MANAGEMENT = 'put sport book management';
    const PERMISSION_GET_WIN_WITHDRAW_MESSAGE = 'get win withdraw message';
    const PERMISSION_POST_WIN_WITHDRAW_MESSAGE = 'post win withdraw message';
    const PERMISSION_DELETE_WIN_WITHDRAW_MESSAGE = 'delete win withdraw message';
    const PERMISSION_GET_SYSTEM_TIMING = 'get system timing';
    const PERMISSION_POST_SYSTEM_TIMING = 'post system timing';
    const PERMISSION_PUT_SYSTEM_TIMING = 'put system timing';
    const PERMISSION_DELETE_SYSTEM_TIMING = 'delete system timing';
    const PERMISSION_GET_BANNER_MAMAGEMENT = 'get banner management';
    const PERMISSION_POST_BANNER_MAMAGEMENT = 'post banner management';
    const PERMISSION_PUT_BANNER_MAMAGEMENT = 'put banner management';
    const PERMISSION_DELETE_BANNER_MAMAGEMENT = 'delete banner management';
    const PERMISSION_POST_BANNER_MAMAGEMENT_UPLOAD_IMAGES = 'post banner management upload images';
    const PERMISSION_GET_REWARDS_MANAGEMENT = 'get rewards management';
    const PERMISSION_POST_REWARDS_MANAGEMENT = 'post rewards management';
    const PERMISSION_PUT_REWARDS_MANAGEMENT = 'put rewards management';
    const PERMISSION_DELETE_REWARDS_MANAGEMENT = 'delete rewards management';
    const PERMISSION_POST_REWARDS_MANAGEMENT_UPLOAD_IMAGES = 'post rewards management upload images';
    const PERMISSION_GET_SEVEN_DAYS_WELFARE = 'get seven days welfare';
    const PERMISSION_POST_SEVEN_DAYS_WELFARE = 'post seven days welfare';
    const PERMISSION_PUT_SEVEN_DAYS_WELFARE = 'put seven days welfare';
    const PERMISSION_DELETE_SEVEN_DAYS_WELFARE = 'delete seven days welfare';
    const PERMISSION_POST_SEVEN_DAYS_WELFARE_UPLOAD_IMAGES = 'delete seven days welfare upload images';
    const PERMISSION_GET_IMPORTANT_NOTICE = 'get important notice';
    const PERMISSION_POST_IMPORTANT_NOTICE = 'post important notice';
    const PERMISSION_PUT_IMPORTANT_NOTICE = 'put important notice';
    const PERMISSION_DELETE_IMPORTANT_NOTICE = 'delete important notice';
    const PERMISSION_PUT_TEST_IP_WHITE_LIST = 'put test ip white list';
    const PERMISSION_PUT_FOR_BIDDEN_IP_LOGIN = 'put for bidden ip login';
    const PERMISSION_GET_ELEMENT_UI_AVATAR = 'get element ui avatar';
    const PERMISSION_POST_ELEMENT_UI_AVATAR = 'post element ui avatar';
    const PERMISSION_PUT_ELEMENT_UI_AVATAR = 'put element ui avatar';
    const PERMISSION_DELETE_ELEMENT_UI_AVATAR = 'delete element ui avatar';
    const PERMISSION_POST_USER_AVATAR_IMAGES = 'post user avatar images';
    const PERMISSION_GET_SIGN_IN_GOLD = 'get sing in gold';
    const PERMISSION_PUT_SIGN_IN_GOLD = 'put sing in gold';
    const PERMISSION_GET_LIST_PLATFORMS = 'get list platforms';
    const PERMISSION_GET_PLATFORMS = 'get platform';
    const PERMISSION_POST_PLATFORMS = 'create platforms';
    const PERMISSION_PUT_PLATFORMS = 'update platforms';
    const PERMISSION_PUT_PLATFORMS_CATEGORIES = 'update platform categories';
    const PERMISSION_GET_LIST_PLATFORMS_CATEGORIES = 'get list platform categories';
    const PERMISSION_DELETE_PLATFORMS = 'delete platforms';
    const PERMISSION_GET_GAME_CATEGORIES = 'get list game categories';
    const PERMISSION_POST_GAME_CATEGORIES = 'create game categories';
    const PERMISSION_PUT_GAME_CATEGORIES = 'update game categories';
    const PERMISSION_DELETE_GAME_CATEGORIES = 'delete game categories';
    const PERMISSION_GET_GAMES = 'get list games';
    const PERMISSION_POST_GAMES = 'create games';
    const PERMISSION_PUT_GAMES = 'update games';
    const PERMISSION_DELETE_GAMES = 'delete games';
    const PERMISSION_GET_ACCOUNT_SUSPENSION_REASON = 'reason AccountSuspension';
    const PERMISSION_GET_ACCOUNT_SUSPENSION = 'get AccountSuspension';
    const PERMISSION_GET_ACCOUNT_SUSPENSION_STATUS = 'status AccountSuspension';
    const PERMISSION_POST_ACCOUNT_SUSPENSION = 'create AccountSuspension';
    const PERMISSION_PUT_ACCOUNT_SUSPENSION = 'update AccountSuspension';
    const PERMISSION_DELETE_ACCOUNT_SUSPENSION = 'destroy AccountSuspension';
    const PERMISSION_POST_VERIFICATION_CODE_QUERY = 'create VerificationCodeQuery';
    const PERMISSION_GET_VIP_RECHARGE = 'get VipRecharge';
    const PERMISSION_POST_VIP_RECHARGE = 'create VipRecharge';
    const PERMISSION_GET_SHARE_ADDRESS = 'get ShareAddress';
    const PERMISSION_PUT_SHARE_ADDRESS = 'update ShareAddress';
    const PERMISSION_POST_TASK_MANAGEMENT_IMAGE_UPLOAD = 'post TaskManagement.imageUpload';
    const PERMISSION_GET_TASK_MANAGEMENT_LIST = 'get TaskManagement.list';
    const PERMISSION_POST_TASK_MANAGEMENT_STORE = 'post TaskManagement.store';
    const PERMISSION_DELETE_TASK_MANAGEMENT_DESTROY = 'delete TaskManagement.destroy';
    const PERMISSION_PUT_TASK_MANAGEMENT_UPDATE = 'put TaskManagement.update';
    const PERMISSION_GET_TASK_MANAGEMENT_DAILY_LIST = 'get TaskManagement.dailyList';
    const PERMISSION_GET_TASK_MANAGEMENT_COMPANY_LIST = 'get TaskManagement.companyList';
    const PERMISSION_GET_TASK_MANAGEMENT_ITEM_LIST = 'get TaskManagement.itemList';
    const PERMISSION_GET_TASK_MANAGEMENT_GET_RECORD = 'get TaskManagement.getRecord';
    const PERMISSION_GET_TASK_MANAGEMENT_GET_DETAILS = 'get TaskManagement.getDetails';
    const PERMISSION_GET_FEEDBACK_LIST = 'get Feedback.list';
    const PERMISSION_GET_FEEDBACK_GET = 'get Feedback.getOperator';
    const PERMISSION_GET_FEEDBACK_UPDATE = 'get Feedback.updateReply';
    const PERMISSION_GET_FEEDBACK_DELETE = 'get Feedback.destroy';
    const PERMISSION_GET_IP_QUERY = 'get IPQuery';
    const PERMISSION_GET_USER_LOGIN_LOG = 'get UserLoginLog';
    const PERMISSION_GET_IP_WHITELIST = 'get IPWhitelist';
    const PERMISSION_POST_IP_WHITELIST = 'post IPWhitelist';
    const PERMISSION_DELETE_IP_WHITELIST = 'delete IPWhitelist';
    const PERMISSION_GET_CLIENT_IP_MANAGEMENT = 'get ClientIPManagement';
    const PERMISSION_PUT_CLIENT_IP_MANAGEMENT = 'put ClientIPManagement';
    const PERMISSION_GET_SENSETIVE_OPERATION_LOG = 'get SensetiveOperationLog';
    const PERMISSION_GET_ALL_PLAYER_QUERY = 'get AllPlayersQuery.index';
    const PERMISSION_GET_USERS_CHANNEL = 'get AllPlayersQuery.channel';
    const PERMISSION_GET_USERS_VIP_LEVEL = 'get AllPlayersQuery.vipLevel';
    const PERMISSION_POST_NEWBIE_MAIL = 'get NewbieMailController.store';
    const PERMISSION_GET_FINANCE_CURRENCIES = 'get finance currencies';
    const PERMISSION_GET_FINANCE_CURRENCIES_WITHDRAW = 'get finance currencies withdraw';
    const PERMISSION_GET_FINANCE_USER_INFO = 'get finance user info';
    const PERMISSION_POST_FINANCE_WITHDRAW = 'post finance withdraw';
    const PERMISSION_GET_FINANCE_WITHDRAW = 'get finance withdraw';
    const PERMISSION_PUT_FINANCE_WITHDRAW_CHANGE_STATUS = 'get finance withdraw change status';
    const PERMISSION_GET_FINANCE_WITHDRAW_STATES = 'get finance withdraw states';
    const PERMISSION_PUT_STOP_SERVICE_ANNOUNCEMENT = 'post StopServiceAnnouncement.store';
    const PERMISSION_GET_GET_ALL_OPERATOR = 'get CustomerServiceOperator.getAllOperators';
    const PERMISSION_GET_MAILING_LIST = 'get MailingList.index';
    const PERMISSION_DELETE_MAILING_LIST = 'delete MailingList.destroy';
    const PERMISSION_POST_MAILING_LIST = 'post MailingList.create';
    const PERMISSION_POST_MASS_MAILING_LIST = 'post MassMailingList.create';
    const PERMISSION_GET_PLAYER_ONLINE_STATISTICS = 'get statistics players online';
    const PERMISSION_GET_PLAYER_ONLINE_STATISTICS_LATEST = 'get statistics players online latest';
    const PERMISSION_GET_PLAYER_ONLINE_STATISTICS_COUNT_BY_DEVICES = 'get statistics players online count by devices';
    const PERMISSION_GET_PLAYER_ONLINE_STATISTICS_USER_INFO_IN_GAME = 'get statistics players online user info in game';


    /**
     * @param array $exclusives Exclude some permissions from the list
     * @return array
     */
    public static function permissions(array $exclusives = []): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $constants = $class->getConstants();
            $permissions = Arr::where($constants, function($value, $key) use ($exclusives) {
                return !in_array($value, $exclusives) && Str::startsWith($key, 'PERMISSION_');
            });

            return array_values($permissions);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }

    public static function menuPermissions(): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $constants = $class->getConstants();
            $permissions = Arr::where($constants, function($value, $key) {
                return Str::startsWith($key, 'PERMISSION_VIEW_MENU_');
            });

            return array_values($permissions);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }

    /**
     * @return array
     */
    public static function roles(): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $constants = $class->getConstants();
            $roles =  Arr::where($constants, function($value, $key) {
                return Str::startsWith($key, 'ROLE_');
            });

            return array_values($roles);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }
}
