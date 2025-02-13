<?php

namespace App\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Chat\Models\QuestionType
 *
 * @property int $id
 * @property string $name
 * @property string $name_local
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuestionType extends Model
{
    use HasFactory;
}
