<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersAndOperatorColumnPhone extends Migration
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
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable();
                }
            });
        }
        if (Schema::hasTable ('customer_service_operators')) {
            Schema::table('customer_service_operators', function (Blueprint $table) {
                if (!Schema::hasColumn('customer_service_operators', 'phone')) {
                    $table->string('phone')->nullable();
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
                if (Schema::hasColumn('messages', 'phone')) {
                    $table->dropColumn('phone');
                }
            });
        }
        if (Schema::hasTable ('customer_service_operators')) {
            Schema::table('customer_service_operators', function (Blueprint $table) {
                if (Schema::hasColumn('customer_service_operators', 'phone')) {
                    $table->dropColumn('phone');
                }
            });
        }
    }
}
