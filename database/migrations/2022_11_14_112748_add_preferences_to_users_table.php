<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreferencesToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('preferences_iamlookingfor');
            $table->string('preferences_age');
            $table->string('preferences_height');
            $table->string('preferences_maxdistance');
            $table->string('preferences_ethnicity');
            $table->string('preferences_religion');
            $table->string('preferences_education');
            $table->string('preferences_relationshipgoals');
            $table->string('preferences_kids');
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
            //
        });
    }
}
