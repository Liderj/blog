<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCategoryForcategoryTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('category', function (Blueprint $table) {
      $table->integer('view')->default(0)->change();
      $table->integer('pid')->default(0)->change();
      $table->string('title')->nullable()->change();
      $table->string('keywords')->nullable()->change();
      $table->string('description')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('category', function (Blueprint $table) {
      //
    });
  }
}
