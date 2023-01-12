<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('status')->default(1);
            $table->string('preferences_iamlookingfor')->nullable();
            $table->integer('preferences_age')->nullable();
            $table->integer('preferences_height')->nullable();
            $table->string('preferences_maxdistance')->nullable();
            $table->string('preferences_ethnicity')->nullable();
            $table->string('preferences_religion')->nullable();
            $table->string('preferences_education')->nullable();
            $table->string('preferences_relationshipgoals')->nullable();
            $table->integer('preferences_kids')->nullable();
            $table->string('gender')->nullable();
            $table->string('profession')->nullable();
            $table->string('relationship_status')->nullable();
            $table->integer('height')->nullable();
            $table->integer('age')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('home_location')->nullable();
            $table->string('profile_prompts')->nullable();
            $table->string('body_type')->nullable();
            $table->string('exercise')->nullable();
            $table->integer('kids')->nullable();
            $table->string('relationship_goals')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('religion')->nullable();
            $table->string('profile_video')->nullable();
            $table->string('my_interests')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
