<?php

use Illuminate\Database\Migrations\Migration;

class AddRoleCustomerServiceOperator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $issetRole = DB::table('roles')
                ->where('name', 'Customer Service Operator')
                ->first();

        if(empty($issetRole)) {
            DB::table('roles')->insert(
                array(
                    'name' => 'Customer Service Operator',
                    'guard_name' => 'api',
                    'alias' => 'CustomerServiceOperator',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                )
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
