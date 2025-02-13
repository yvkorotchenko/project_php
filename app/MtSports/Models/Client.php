<?php

namespace App\MtSports\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\MtSports\Models\Client
 *
 * @property int $id
 * @property int $pid
 * @property string $typ 用户类型: normal正常, try试玩
 * @property string $country_code 国际区号
 * @property string $phone
 * @property string $login_password
 * @property string $nickname
 * @property string $avatar 头像
 * @property int $grade
 * @property string $apple_account
 * @property string $facebook_account
 * @property string $google_account
 * @property int $balance 账户余额
 * @property int $limit_amount 游戏资金限制
 * @property string $is_enable 用户可用状态
 * @property int $reg_ip 注册ip
 * @property int $last_login_ip 最后登录ip
 * @property int $last_login_time 最后登录时间
 * @property string $created
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAppleAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereFacebookAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereGoogleAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereIsEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereLastLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereLimitAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereLoginPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereRegIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereTyp($value)
 * @mixin \Eloquent
 * @property string $email
 * @property string|null $device_id
 * @property string $sex
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereSex($value)
 * @property int $updated_at
 * @property int $vip_level
 * @property int $withdraw_standard
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereVipLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereWithdrawStandard($value)
 * @property int $source_channel_id
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereSourceChannelId($value)
 */
class Client extends Model
{
    use HasFactory;

    protected $table = 'user';
    protected $connection = 'mtsports';
    public $timestamps = false;
}
