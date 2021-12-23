<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_store', function (Blueprint $table) {
            $table->bigInteger('goods_id')->unsigned();
            $table->bigInteger('store_id')->unsigned();
            $table->boolean('hot')->default(0);
            $table->boolean('recommend')->default(0);
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->foreign('store_id')->references('id')->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_store');
    }
}
