<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('news')){
            Schema::create('news', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('url')->nullable();
                $table->string('min_img')->nullable();
                $table->string('big_img')->nullable();
                $table->string('announcement_text')->nullable();
                $table->text('content');
                $table->enum('visible', [1,0])->default(1);
                $table->integer('view');
                $table->string('date')->nullable();
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
        Schema::dropIfExists('news');
    }
}
