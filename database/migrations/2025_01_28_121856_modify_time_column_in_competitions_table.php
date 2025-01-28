<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTimeColumnInCompetitionsTable extends Migration
{
    public function up()
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->string('time')->change(); // Change the type from datetime to string
        });
    }

    public function down()
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dateTime('time')->change(); // Reverse the change if needed
        });
    }
}
