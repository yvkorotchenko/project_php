<?php

namespace App\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Chat\Models\ChatAnon
 *
 * @property int $id
 * @property string|null $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnon query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnon whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnon whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChatAnon extends Model
{
    use HasFactory;
}
