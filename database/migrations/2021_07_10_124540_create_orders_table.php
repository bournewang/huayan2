<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('orderNo')->nullable();
            $table->string('payNo')->nullable();
            $table->decimal('orderAmount', 8,2)->nullable();
            $table->datetime('orderTime')->nullable();
            $table->datetime('payTime')->nullable();
            $table->string('buyerRegNo')->nullable();
            $table->string('buyerName')->nullable();
            $table->string('buyerTelephone')->nullable();
            $table->string('buyerIdNumber')->nullable();
            $table->string('consignee')->nullable();
            $table->string('consigneeTelephone')->nullable();
            $table->string('consigneeAddress')->nullable();
            $table->string('receiverProvince')->nullable();
            $table->string('receiverCity')->nullable();
            $table->string('receiverCounty')->nullable();
            $table->string('payRequest')->nullable();
            $table->string('payResponse')->nullable();
            $table->string('orderInfoList')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
