<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_task', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_id');
            $table->unsignedBigInteger('task_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_task');
    }
}
