<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvatarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('avatars')) {
            Schema::create('avatars', function (Blueprint $table) {
                $table->id();
                $table->string('name', 37)->unique();
                $table->string('img_url', 255);
                $table->enum('visible', ['0', '1'])->default('1');
                $table->integer('position', false, true);
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
        Schema::dropIfExists('avatars');
    }
}
