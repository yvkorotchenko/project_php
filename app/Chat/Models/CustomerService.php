<?php

namespace App\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Chat\Models\CustomerService
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Chat\Models\CustomerServiceOperator[] $operators
 * @property-read int|null $operators_count
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerService query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerService whereName($value)
 * @mixin \Eloquent
 */
class CustomerService extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
    ];

    public function operators(): HasMany
    {
        return $this->hasMany(CustomerServiceOperator::class);
    }
}
