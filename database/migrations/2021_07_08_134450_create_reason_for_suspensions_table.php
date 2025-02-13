<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Laravue\Models\ReasonForSuspension;

class CreateReasonForSuspensionsTable extends Migration
{
    private const ReasonForSuspensionsNames = [
        'Illegal currency swiping',
        'Suspected of fraud',
        'Scalping chips',
        'Pass card cheating',
        'Abnormal operation',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable('reason_for_suspensions')){
            Schema::create('reason_for_suspensions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        foreach (self::ReasonForSuspensionsNames as $name) {
            $issetName = ReasonForSuspension::where('name', '=', $name)->first();
            if(empty($issetName)){
                $model = new ReasonForSuspension;
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
        Schema::dropIfExists('reason_for_suspensions');
    }
}
