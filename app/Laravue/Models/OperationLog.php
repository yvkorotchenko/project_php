<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Laravue\Models\OperationLog
 *
 * @property int $id
 * @property string $name
 * @property string $action_en
 * @property string $action_zh
 * @property string $type_req
 * @property string $path_req
 * @property string $data_req
 * @property string $ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereDataReq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog wherePathReq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereTypeReq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OperationLog extends Model
{

    protected $fillable = [
        'name',
        'action_en',
        'action_zh',
        'type_req',
        'path_req',
        'data_req',
        'ip',
    ];
}
