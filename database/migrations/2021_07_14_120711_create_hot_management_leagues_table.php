<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotManagementLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('hot_management_leagues')){
            Schema::create('hot_management_leagues', function (Blueprint $table) {
                $table->id();
                $table->integer('gameId',);
                $table->text('ids');
                $table->smallInteger('days')->default(30);
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
        Schema::dropIfExists('hot_management_leagues');
    }
}
