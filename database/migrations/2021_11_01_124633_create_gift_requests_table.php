<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('gift_id')->unsigned();
            $table->text('url');
            $table->string('mobile');
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
            $table->integer('deleted_at')->nullable();
        });

        Schema::table('gift_requests', function (Blueprint $table) {
            $table->foreign('gift_id')->references('id')->on('gifts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_requests');
    }
}
