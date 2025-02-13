<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable ('action_logs')) {
            Schema::create('action_logs', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable();
                $table->string('user_name')->nullable();
                $table->text('action')->nullable();
                $table->timestamps();
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

        Schema::dropIfExists('action_logs');

    }
}
