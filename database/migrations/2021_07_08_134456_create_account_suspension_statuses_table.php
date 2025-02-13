<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Laravue\Models\AccountSuspensionStatus;

class CreateAccountSuspensionStatusesTable extends Migration
{
    private const AccountSuspensionStatusNames = [
        'Suspended',
        'Expired',
        'Unblocked',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('account_suspension_statuses')){
            Schema::create('account_suspension_statuses', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }
        
        foreach (self::AccountSuspensionStatusNames as $name) {
            $issetName = AccountSuspensionStatus::where('name', '=', $name)->first();
            if(empty($issetName)){
                $model = new AccountSuspensionStatus;
                $model->name = $name;
                $model->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_suspension_statuses');
    }
}
