<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Laravue\Models\Role;

class ChangeTableRoles extends Migration
{

    private const roles = [
        [ 'name' => 'admin', 'name_zh' => '系统管理员'],
        [ 'name' => 'manager', 'name_zh' => '经理'],
        [ 'name' => 'user', 'name_zh' => '一般用户'],
        [ 'name' => 'root', 'name_zh' => '最高管理员'],
        [ 'name' => 'Customer Service Operator', 'name_zh' => '文字客服服务员'],
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // roles
        if(Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table)
            {
                if (!Schema::hasColumn('roles', 'name_zh')) {
                    $table->string('name_zh')->nullable();
                }
            });
        }
        foreach(self::roles as $role) {
            $issetRole = Role::where('name', '=', $role['name'])->first();
            if (!empty($issetRole)) {
                $issetRole->name_zh = $role['name_zh'];
                $issetRole->save();
            }
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
