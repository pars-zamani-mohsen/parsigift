<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gifts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('amount')->nullable();
            $table->float('qty')->default(0);
            $table->boolean('active')->default(0);
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
            $table->integer('deleted_at')->nullable();
            $table->bigInteger('created_by')->unsigned();
        });

        Schema::table('gifts', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gifts');
    }
}
