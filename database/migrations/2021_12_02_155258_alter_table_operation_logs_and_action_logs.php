<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableOperationLogsAndActionLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // action_logs
        if(Schema::hasTable('action_logs')) {
            Schema::table('action_logs', function (Blueprint $table)
            {
                if (Schema::hasColumn('action_logs', 'action')) {
                    $table->renameColumn('action', 'action_en');
                }
                if (!Schema::hasColumn('action_logs', 'action_zh')) {
                    $table->string('action_zh')->nullable();
                }
            });
        }
        // operation_logs
        if(Schema::hasTable('operation_logs')) {
            Schema::table('operation_logs', function (Blueprint $table)
            {
                if (Schema::hasColumn('operation_logs', 'action')) {
                    $table->renameColumn('action', 'action_en');
                }
                if (!Schema::hasColumn('operation_logs', 'action_zh')) {
                    $table->string('action_zh')->nullable();
                }
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
        //
    }
}
