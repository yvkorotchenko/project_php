<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatHistoryMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('chat_history_messages')){
            Schema::create('chat_history_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('chat_history_id')->nullable(false);
                $table->enum('from', ['client', 'operator']);
                $table->text('message_content')->nullable(false);
                $table->text('media_uri')->nullable(true);
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
        Schema::dropIfExists('chat_history_messages');
    }
}
