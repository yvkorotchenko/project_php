<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class QuestionTypeAddMultilang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('question_types', 'name_local')) {
            Schema::table('question_types', function (Blueprint $table) {
                $table->string('name_local');
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
        if (Schema::hasColumn('question_types', 'name_local')) {
            Schema::table('question_types', function (Blueprint $table) {
                $table->dropColumn('name_local');
            });
        }
    }
}
