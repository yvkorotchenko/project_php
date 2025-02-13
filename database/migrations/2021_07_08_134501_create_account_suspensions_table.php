<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountSuspensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable('account_suspensions')){
            Schema::create('account_suspensions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('account_id');
                $table->integer('banned_days');
                $table->unsignedBigInteger('reason_for_suspension_id');
                $table->text('suspension_notes');
                $table->timestamp('unbloking_time');
                $table->unsignedBigInteger('last_operation_user_id');
                $table->unsignedBigInteger('status_id');
                $table->timestamps();

                $table->foreign('reason_for_suspension_id')->references('id')->on('reason_for_suspensions');
                $table->foreign('last_operation_user_id')->references('id')->on('users');
                $table->foreign('status_id')->references('id')->on('account_suspension_statuses');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_suspensions');
    }
}
