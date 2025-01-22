<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('code');
            $table->unsignedBigInteger('name');
            $table->unsignedBigInteger('author');
            $table->timestamp('submitted');
            $table->string('programming_language');
            $table->integer('time_taken');
            $table->integer('memory');
            $table->string('status');
            $table->text('tests');
            $table->timestamps();

            // Foreign keys
            $table->foreign('code')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('name')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('author')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submitions');
    }
}
