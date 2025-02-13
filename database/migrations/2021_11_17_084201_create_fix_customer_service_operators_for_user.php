<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateFixCustomerServiceOperatorsForUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // проверять если таблица customer_services - если нет то создавать
        if(!Schema::hasTable('customer_services')) {
            Schema::table('customer_services', function (Blueprint $table) {
                $table->id('id');
                $table->string('name');
            });
            DB::table('customer_services')
                ->insert([
                    'id' => 1,
                    'name' => 'Test Customer service',
                ]);
        }
        // очищаем таблицу customer_services
        DB::statement('truncate table customer_services');
        // проверить если тестовая компания
        $issetCustomerServices = DB::table('customer_services')
            ->where('id', 1)
            ->first();
        if (empty($issetCustomerServices)) {
            DB::table('customer_services')
                ->insert([
                    'id' => 1,
                    'name' => 'Test Customer service',
                ]);
        }
        // проверять если таблица customer_service_operators - если нет то создавать
        if(!Schema::hasTable('customer_service_operators')) {
            Schema::table('customer_service_operators', function (Blueprint $table) {
                $table->id('id');
                $table->string('name');
                $table->integer('customer_service_id')->default(1);
                $table->timestamps();
                $table->string('email');
                $table->string('password');
                $table->string('phone')->nullable();
            });
        } else {
            // изменить поле customer_service_id = поставить null и по умолчанию "1"
            if (Schema::hasColumn('customer_service_operators', 'customer_service_id')) {
                Schema::table('customer_service_operators', function (Blueprint $table) {
                    $table->string('customer_service_id')->default(1)->change();
                });
            } else {
                Schema::table('customer_service_operators', function (Blueprint $table) {
                    $table->integer('customer_service_id')->default(1);
                });
            }
        }
        // очистить таблицу
        DB::statement('truncate table customer_service_operators');
        // пройтись по всем пользователям и добавить в таблицу customer_service_operators тех у кого есть роль customer_service_operators
        $allUsers = User::all();
        if($allUsers){
            foreach ($allUsers as $user) {
                $issetUserRoleCSO = DB::table('model_has_roles')
                    ->where('model_id', $user->id)
                    ->where('role_id', 11)
                    ->first();
                if ($issetUserRoleCSO) {
                    DB::table('customer_service_operators')
                        ->insert([
                            'id' => $user->id,
                            'name' => $user->name,
                            'created_at' => $user->created_at,
                            'updated_at' => $user->updated_at,
                            'email' => $user->nick,
                            'password' => $user->password,
                            'phone' => $user->phone
                        ]);
                }
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
        Schema::dropIfExists('fix_customer_service_operators_for_user');
    }
}
