<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->string('shopId');
            $table->bigInteger('category_id')->unsigned();
            $table->string('name');
            $table->string('qty')->nullable(); 
            $table->string('type')->nullable();
            $table->string('brand')->nullable();
            $table->string('saleFlag')->nullable();
            $table->string('price')->nullable();
            $table->string('img')->nullable();
            $table->string('img_s')->nullable();
            $table->string('img_m')->nullable();
            $table->string('pv')->nullable();
            $table->string('saleCount')->nullable();
            $table->string('customs_id')->nullable();
            $table->text('detail')->nullable();
            $table->integer('commission')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
