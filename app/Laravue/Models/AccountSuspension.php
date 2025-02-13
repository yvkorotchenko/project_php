<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Laravue\Models\AccountSuspension
 *
 * @property int $id
 * @property int $account_id
 * @property int $banned_days
 * @property int $reason_for_suspension_id
 * @property string $suspension_notes
 * @property string $unbloking_time
 * @property int $last_operation_user_id
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension whereBannedDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension whereLastOperationUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension whereReasonForSuspensionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension whereSuspensionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension whereUnblokingTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountSuspension whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AccountSuspension extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_id',
        'banned_days',
        'reason_for_suspension_id',
        'suspension_notes',
        'unbloking_time',
        'last_operation_user_id',
        'status_id',
        'created_at',
        'updated_at']; 

    public function getAll($req){
        // $res = new AccountSuspension;
        $res = DB::table('account_suspensions');
        ($req->lastOperationUserId)? $res->where('last_operation_user_id',$req->lastOperationUserId):'';
        ($req->accountId)? $res->where('account_id',$req->accountId):'';
        ($req->dateStart)? $res->where('created_at','>=',$req->dateStart):'';
        ($req->dateEnd)? $res->where('created_at','<=',$req->dateEnd):'';
        $total = $res->count();
        ($req->limit)? $res->take($req->limit):'';
        ($req->page && $req->limit)? $res->skip(($req->page-1)*$req->limit):'';

        return [
            'data' => $res->get(),
            'total' => $total
        ];
    }
}
