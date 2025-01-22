<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCompetitionsAndTasksTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->string('difficulty')->after('information');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->json('correct_answer')->after('examples');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn('difficulty');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('correct_answer');
        });
    }
}
