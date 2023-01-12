<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender');
            $table->string('profession');
            $table->string('relationship_status');
            $table->string('height');
            $table->string('age');
            $table->string('current_location');
            $table->string('home_location');
            $table->string('profile_prompts');
            $table->string('body_type');
            $table->string('exercise');
            $table->string('kids');
            $table->string('relationship_goals');
            $table->string('ethnicity');
            $table->string('religion');
            $table->string('profile_video');
            $table->string('my_interests');
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
