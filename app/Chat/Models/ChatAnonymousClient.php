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
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnonymousClient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnonymousClient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnonymousClient query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnonymousClient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnonymousClient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnonymousClient whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatAnonymousClient whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChatAnonymousClient extends Model
{
    protected $table = 'chat_anons';
    use HasFactory;
}
