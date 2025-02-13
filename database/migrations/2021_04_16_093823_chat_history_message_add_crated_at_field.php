<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChatHistoryMessageAddCratedAtField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('chat_history_messages', 'created_at')) {
            Schema::table('chat_history_messages', function(Blueprint $table) {
                $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::table('chat_history_messages', function(Blueprint $table) {
            $table->removeColumn('created_at');
        });
    }
}
