<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('r_and_d_check')->default(0)->nullable();
            $table->string('nesbat')->nullable();
            $table->string('cart_number')->nullable();
            $table->integer('last_activity_time')->nullable();
            $table->string('device')->nullable();
            $table->string('ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['r_and_d_check', 'nesbat', 'cart_number', 'last_activity_time', 'device', 'ip']);
        });
    }
}
