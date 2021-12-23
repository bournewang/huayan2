<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned()->nullable();
            $table->bigInteger('senior_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('openid', 64)->nullable();
            $table->string('nickname', 32)->nullable();
            $table->string('avatar')->nullable();
            $table->integer('gender')->nullable();
            $table->string('mobile', 24)->nullable();
            $table->string('province', 32)->nullable();
            $table->string('city', 32)->nullable();
            $table->string('county', 32)->nullable();
            $table->string('qrcode', 64)->nullable();
            $table->string('id_no', 24)->nullable();
            $table->string('id_img1', 128)->nullable();
            $table->string('id_img2', 128)->nullable();
            $table->decimal('ppv', 8, 2)->nullable();
            $table->decimal('gpv', 8, 2)->nullable(); 
            $table->decimal('tgpv', 8, 2)->nullable(); // 
            $table->decimal('pgpv', 8, 2)->nullable(); // tgpv - qualified directors' tgpv
            $table->decimal('agpv', 8, 2)->nullable();
            $table->decimal('income_ratio', 8, 2)->nullable();
            $table->decimal('retail_income', 8, 2)->nullable();
            $table->decimal('level_bonus', 8, 2)->nullable();
            $table->decimal('leader_bonus', 8, 2)->nullable();
            $table->decimal('width_bonus', 8, 2)->nullable();
            $table->decimal('depth_bonus', 8, 2)->nullable();
            $table->decimal('total_income', 8, 2)->nullable();
            $table->integer('level')->nullable()->nullable();
            $table->boolean('dd')->default(0);
            $table->integer('dds')->default(0);
            $table->boolean('hlb')->default(0);
            $table->decimal('lbpv', 8, 2)->nullable();
            // $table->decimal('tlbpv', 8, 2)->nullable();
            $table->string('apply_status')->nullable();
            // $table->integer('sharing')->unsigned()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('api_token', 80)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('senior_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
