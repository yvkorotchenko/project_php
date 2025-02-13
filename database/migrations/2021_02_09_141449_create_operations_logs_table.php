<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('operation_logs')){
            Schema::create('operation_logs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('action');
                $table->string('type_req');
                $table->string('path_req');
                $table->text('data_req');
                $table->string('ip');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent();
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
        Schema::dropIfExists('operation_logs');
    }
}
