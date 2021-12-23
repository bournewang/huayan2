<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('province_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->bigInteger('district_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('consignee', 12);
            $table->string('telephone', 24);
            // $table->string('province', 32);
            // $table->string('city', 32);
            // $table->string('county', 32)->nullable();
            $table->string('street');
            $table->boolean('default');
            $table->timestamps();
            $table->softDeletes();
            
            // $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('district_id')->references('id')->on('districts');
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
        Schema::dropIfExists('addresses');
    }
}
