<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleTokenAndVereficationCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable ('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'google_token')) {
                    $table->string('google_token', 67);
                }
                if (!Schema::hasColumn('users', 'google_code')) {
                    $table->string('google_code', 7);
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
        if (Schema::hasTable ('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'google_token')) {
                    $table->dropColumn('google_token');
                }
                if (Schema::hasColumn('users', 'google_code')) {
                    $table->dropColumn('google_code');
                }
            });
        }
    }
}
