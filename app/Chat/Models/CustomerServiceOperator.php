<?php

namespace App\Chat\Models;

use App\Laravue\Models\OperatorToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Chat\Models\CustomerServiceOperator
 *
 * @property int $id
 * @property string $name
 * @property int $customer_service_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Chat\Models\CustomerService $customerSerivce
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerServiceOperator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerServiceOperator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerServiceOperator query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerServiceOperator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerServiceOperator whereCustomerServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerServiceOperator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerServiceOperator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerServiceOperator whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $email
 * @property string $password
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerServiceOperator whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerServiceOperator wherePassword($value)
 */
class CustomerServiceOperator extends User implements JWTSubject
{
    use HasFactory;

    public $fillable = [
        'name',
        'customer_service_id',
        'email',
        'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function customerSerivce(): BelongsTo
    {
        return $this->belongsTo(CustomerService::class, 'customer_service_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
