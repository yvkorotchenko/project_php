<?php

namespace App\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Chat\Models\ChatHistoryMessage
 *
 * @property int $id
 * @property int $chat_history_id
 * @property string $from
 * @property string $message_content
 * @property string|null $media_uri
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistoryMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistoryMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistoryMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistoryMessage whereChatHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistoryMessage whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistoryMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistoryMessage whereMediaUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistoryMessage whereMessageContent($value)
 * @mixin \Eloquent
 * @property-read \App\Chat\Models\ChatHistory $chatHistory
 * @property \Illuminate\Support\Carbon $crated_at
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistoryMessage whereCratedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistoryMessage whereCreatedAt($value)
 */
class ChatHistoryMessage extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    public $fillable = [
        'from',
        'message_content',
        'media_url',
    ];

    public function getDates()
    {
        return ['crated_at'];
    }

    public function chatHistory(): BelongsTo
    {
        return $this->belongsTo(ChatHistory::class);
    }
}
