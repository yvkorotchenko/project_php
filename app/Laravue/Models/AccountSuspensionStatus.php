<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Laravue\Models\AccountSuspensionStatus
 *
 * @property int $id
 * @property string $name_en
 * @property string $name_zh
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspensionStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspensionStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspensionStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspensionStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspensionStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspensionStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspensionStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AccountSuspensionStatus extends Model
{
    use HasFactory;
}
