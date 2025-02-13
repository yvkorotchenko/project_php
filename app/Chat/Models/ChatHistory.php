<?php

namespace App\Chat\Models;

use App\MtSports\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Kirschbaum\PowerJoins\PowerJoins;

/**
 * App\Chat\Models\ChatHistory
 *
 * @property int $id
 * @property int $client_id
 * @property int $customer_service_operator_id
 * @property string $chat_content
 * @property int $question_type_id
 * @property string $chat_date
 * @property int $completed
 * @property-read Client $client
 * @property-read \App\Chat\Models\CustomerServiceOperator $operator
 * @property-read \App\Chat\Models\QuestionType $questionType
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistory whereChatContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistory whereChatDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistory whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistory whereCustomerServiceOperatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistory whereQuestionTypeId($value)
 * @mixin \Eloquent
 * @property int $customer_service_id
 * @property-read \App\Chat\Models\CustomerService $customerService
 * @property-read Collection|\App\Chat\Models\ChatHistoryMessage[] $messages
 * @property-read int|null $messages_count
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistory whereCustomerServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatHistory whereCompleted($value)
 * @property string|null $client_name
 * @method static Builder|ChatHistory hasNestedUsingJoins($relations, $operator = '>=', $count = 1, $boolean = 'and', ?\Closure $callback = null)
 * @method static Builder|ChatHistory joinNestedRelationship(string $relationships, $callback = null, $joinType = 'join', $useAlias = false, bool $disableExtraConditions = false)
 * @method static Builder|ChatHistory joinRelation($relationName, $callback = null, $joinType = 'join', $useAlias = false, bool $disableExtraConditions = false)
 * @method static Builder|ChatHistory joinRelationship($relationName, $callback = null, $joinType = 'join', $useAlias = false, bool $disableExtraConditions = false)
 * @method static Builder|ChatHistory joinRelationshipUsingAlias($relationName, $callback = null, bool $disableExtraConditions = false)
 * @method static Builder|ChatHistory leftJoinRelation($relation, $callback = null, $useAlias = false, bool $disableExtraConditions = false)
 * @method static Builder|ChatHistory leftJoinRelationship($relation, $callback = null, $useAlias = false, bool $disableExtraConditions = false)
 * @method static Builder|ChatHistory leftJoinRelationshipUsingAlias($relationName, $callback = null, bool $disableExtraConditions = false)
 * @method static Builder|ChatHistory orderByLeftPowerJoins($sort, $direction = 'asc')
 * @method static Builder|ChatHistory orderByLeftPowerJoinsAvg($sort, $direction = 'asc')
 * @method static Builder|ChatHistory orderByLeftPowerJoinsCount($sort, $direction = 'asc')
 * @method static Builder|ChatHistory orderByLeftPowerJoinsMax($sort, $direction = 'asc')
 * @method static Builder|ChatHistory orderByLeftPowerJoinsMin($sort, $direction = 'asc')
 * @method static Builder|ChatHistory orderByLeftPowerJoinsSum($sort, $direction = 'asc')
 * @method static Builder|ChatHistory orderByPowerJoins($sort, $direction = 'asc', $aggregation = null, $joinType = 'join')
 * @method static Builder|ChatHistory orderByPowerJoinsAvg($sort, $direction = 'asc')
 * @method static Builder|ChatHistory orderByPowerJoinsCount($sort, $direction = 'asc')
 * @method static Builder|ChatHistory orderByPowerJoinsMax($sort, $direction = 'asc')
 * @method static Builder|ChatHistory orderByPowerJoinsMin($sort, $direction = 'asc')
 * @method static Builder|ChatHistory orderByPowerJoinsSum($sort, $direction = 'asc')
 * @method static Builder|ChatHistory powerJoinDoesntHave($relation, $boolean = 'and', ?\Closure $callback = null)
 * @method static Builder|ChatHistory powerJoinHas($relation, $operator = '>=', $count = 1, $boolean = 'and', ?\Closure $callback = null)
 * @method static Builder|ChatHistory powerJoinWhereHas($relation, ?\Closure $callback = null, $operator = '>=', $count = 1)
 * @method static Builder|ChatHistory rightJoinRelation($relation, $callback = null, $useAlias = false, bool $disableExtraConditions = false)
 * @method static Builder|ChatHistory rightJoinRelationship($relation, $callback = null, $useAlias = false, bool $disableExtraConditions = false)
 * @method static Builder|ChatHistory rightJoinRelationshipUsingAlias($relationName, $callback = null, bool $disableExtraConditions = false)
 * @method static Builder|ChatHistory whereClientName($value)
 */
class ChatHistory extends Model
{
    use HasFactory;
    use PowerJoins;

    public $timestamps = false;

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(CustomerServiceOperator::class, 'customer_service_operator_id');
    }

    public function questionType(): BelongsTo
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function customerService(): BelongsTo
    {
        return $this->belongsTo(CustomerService::class, 'customer_service_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatHistoryMessage::class);
    }
}
