<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OperatorAddAuthFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('customer_service_operators', 'email')) {
            Schema::table('customer_service_operators', function (Blueprint $table) {
                $table->string('email')->nullable(false);
            });
        }
        if (!Schema::hasColumn('customer_service_operators', 'password')) {
            Schema::table('customer_service_operators', function (Blueprint $table) {
                $table->string('password')->nullable(false)->default('');
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
        Schema::table('customer_service_operators', function (Blueprint $table) {
            $table->removeColumn('email');
            $table->removeColumn('password');
        });
    }
}
