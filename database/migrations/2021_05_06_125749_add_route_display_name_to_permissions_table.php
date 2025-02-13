<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRouteDisplayNameToPermissionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
      if(!Schema::hasTable('permissions')){
          Schema::table('permissions', function (Blueprint $table) {
              $table->enum('hidden', ['0', '1'])->default(1);
              $table->integer('sort')->nullable()->default(0);
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
    Schema::table('permissions', function (Blueprint $table) {
      $table->dropColumn('hidden');
      $table->dropColumn('sort');
    });
  }
}
