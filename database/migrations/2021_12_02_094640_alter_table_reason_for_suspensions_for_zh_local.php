<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableReasonForSuspensionsForZhLocal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('reason_for_suspensions');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        if(!Schema::hasTable('reason_for_suspensions')) {
            Schema::create('reason_for_suspensions', function (Blueprint $table) {
                $table->id('id')->autoIncrement();
                $table->string('name_en');
                $table->string('name_zh');
                $table->timestamps();
            });

            DB::table('reason_for_suspensions')
                ->insert([
                        [ 'id' => 1, 'name_en' => 'Illegal currency swiping', 'name_zh' => '非法刷币', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ],
                        [ 'id' => 2, 'name_en' => 'Suspected of fraud', 'name_zh' => '涉嫌欺诈', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ],
                        [ 'id' => 3, 'name_en' => 'Scalping chips', 'name_zh' => '倒卖筹码', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ],
                        [ 'id' => 4, 'name_en' => 'Pass card cheating', 'name_zh' => '通牌作弊', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ],
                        [ 'id' => 5, 'name_en' => 'Abnormal operation', 'name_zh' => '异常操作', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ],
                    ]
                );
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
