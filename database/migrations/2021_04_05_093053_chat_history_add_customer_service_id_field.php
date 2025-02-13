<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChatHistoryAddCustomerServiceIdField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('chat_histories', 'customer_service_id')) {
            Schema::table('chat_histories', function (Blueprint $table) {
                $table->foreignId('customer_service_id')->nullable(false);
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
        Schema::table('chat_histories', function (Blueprint $table) {
            $table->dropColumn('customer_service_id');
        });
    }
}
