<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->comment('分类名称');
            $table->string('title',255)->comment('分类描述');
            $table->string('keywords',255)->comment('关键词');
            $table->string('description',255)->comment('简介');
            $table->integer('view')->comment('查看数量');
            $table->tinyInteger('order')->comment('排序');
            $table->integer('pid')->comment('父级ID');
            $table->timestamps();
            $table->comment= '文章分类';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category');
    }
}