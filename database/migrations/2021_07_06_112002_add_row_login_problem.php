<?php

use App\Chat\Models\QuestionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRowLoginProblem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $issetLoginProblem = DB::table('question_types')
                ->where('name', 'Login problem')
                ->first();
        
        if(empty($issetLoginProblem)){
            DB::table('question_types')->insert(
                array(
                    'name' => 'Login problem'
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
        DB::table('question_types')->where("name","Login problem")->delete();
    }
}
