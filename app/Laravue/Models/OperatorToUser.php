<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Laravue\Models\OperatorToUser
 *
 * @property int $id
 * @property int $user_id
 * @property int $operator_id
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|OperatorToUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OperatorToUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OperatorToUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|OperatorToUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperatorToUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperatorToUser whereOperatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperatorToUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperatorToUser whereUserId($value)
 * @mixin \Eloquent
 */
class OperatorToUser extends Model
{
    use HasFactory;
}
