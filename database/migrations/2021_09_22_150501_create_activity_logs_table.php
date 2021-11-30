<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('log_type', ['create', 'update', 'delete']);
            $table->string('model');
            $table->integer('subject_id');
            $table->bigInteger('user_id')->unsigned();
            $table->longText('content');
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
            $table->integer('deleted_at')->nullable();
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
