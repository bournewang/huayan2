<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('license_no')->nullable();
            $table->string('account_no')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('telphone')->nullable();
            $table->string('license_img')->nullable();   
            $table->string('tier_bonus')->nullable();
            $table->string('leader_bonus')->nullable();
            $table->string('width_bonus')->nullable();
            $table->string('depth_bonus')->nullable();
            // $table->string('year_bonus')->nullable();
            // $table->string('once_bonus')->nullable();
            $table->text('bonus_title')->nullable();
            // $table->integer('commission')->nullable();         
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
