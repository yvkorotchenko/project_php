<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAccountSuspensionStatusesForZhLocal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('account_suspension_statuses');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        if(!Schema::hasTable('account_suspension_statuses')) {
            Schema::create('account_suspension_statuses', function (Blueprint $table) {
                $table->id('id')->autoIncrement();
                $table->string('name_en');
                $table->string('name_zh');
                $table->timestamps();
            });

            DB::table('account_suspension_statuses')
                ->insert([
                        [ 'id' => 1, 'name_en' => 'Suspended', 'name_zh' => '封停中', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ],
                        [ 'id' => 2, 'name_en' => 'Expired', 'name_zh' => '已过期', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ],
                        [ 'id' => 3, 'name_en' => 'Unblocked', 'name_zh' => '已解封', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ],
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
