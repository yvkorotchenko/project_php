<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('chat_histories')){
            Schema::create('chat_histories', function (Blueprint $table) {
                $table->id();
                $table->integer('client_id', false, true);
                $table->integer('customer_service_operator_id', false, true);
                $table->text('chat_content');
                $table->integer('question_type_id', false, true);
                $table->dateTime('chat_date');
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
        Schema::dropIfExists('chat_histories');
    }
}
