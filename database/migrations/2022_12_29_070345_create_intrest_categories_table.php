<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateIntrestCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $timestamps = true;
    public function up()
    {
        Schema::create('intrest_categories', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('parent_id');
            // $table->foreign('parent_id')->references('id')->on('intrest_categories');
            $table->integer('parent_id');
            $table->string('name')->nullable();
            $table->integer('sequence')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
        DB::table('intrest_categories')->insert([
            // Parent record
            ['parent_id' => 0, 'name' => 'Food & Drinks', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 0, 'name' => 'Creativity', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 0, 'name' => 'Movies & TV', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 0, 'name' => 'Sports', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 0, 'name' => 'Staying in', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 0, 'name' => 'Going out', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 0, 'name' => 'Music', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 0, 'name' => 'Travel & Explore', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 0, 'name' => 'Pets', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 0, 'name' => 'Reading', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 0, 'name' => 'Values & character', 'sequence' => '1', 'status' => '1'],

            // Child record
            ['parent_id' => 1, 'name' => 'Coffee', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Beer', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Boba tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Sushi', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Vegetarian', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Barbecue', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Brunch', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Cocktails', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Pizza', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Whiskey', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Wine', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Foodie', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 1, 'name' => 'Tacos Gin', 'sequence' => '1', 'status' => '1'],
            
            ['parent_id' => 2, 'name' => 'Coffee', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Beer', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Boba tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Sushi', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Vegetarian', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Barbecue', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Brunch', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Cocktails', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Pizza', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Whiskey', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Wine', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Foodie', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 2, 'name' => 'Tacos Gin', 'sequence' => '1', 'status' => '1'],

            ['parent_id' => 3, 'name' => 'Coffee', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Beer', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Boba tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Sushi', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Vegetarian', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Barbecue', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Brunch', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Cocktails', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Pizza', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Whiskey', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Wine', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Foodie', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 3, 'name' => 'Tacos Gin', 'sequence' => '1', 'status' => '1'],

            ['parent_id' => 4, 'name' => 'Coffee', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Beer', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Boba tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Sushi', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Vegetarian', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Barbecue', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Brunch', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Cocktails', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Pizza', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Whiskey', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Wine', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Foodie', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 4, 'name' => 'Tacos Gin', 'sequence' => '1', 'status' => '1'],

            ['parent_id' => 5, 'name' => 'Coffee', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Beer', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Boba tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Sushi', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Vegetarian', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Barbecue', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Brunch', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Cocktails', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Pizza', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Whiskey', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Wine', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Foodie', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 5, 'name' => 'Tacos Gin', 'sequence' => '1', 'status' => '1'],

            ['parent_id' => 6, 'name' => 'Coffee', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Beer', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Boba tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Sushi', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Vegetarian', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Barbecue', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Brunch', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Cocktails', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Pizza', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Whiskey', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Wine', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Foodie', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 6, 'name' => 'Tacos Gin', 'sequence' => '1', 'status' => '1'],

            ['parent_id' => 7, 'name' => 'Coffee', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Beer', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Boba tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Sushi', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Vegetarian', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Barbecue', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Brunch', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Cocktails', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Pizza', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Whiskey', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Wine', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Foodie', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 7, 'name' => 'Tacos Gin', 'sequence' => '1', 'status' => '1'],

            ['parent_id' => 8, 'name' => 'Coffee', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Beer', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Boba tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Sushi', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Vegetarian', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Barbecue', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Brunch', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Cocktails', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Pizza', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Whiskey', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Wine', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Foodie', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 8, 'name' => 'Tacos Gin', 'sequence' => '1', 'status' => '1'],

            ['parent_id' => 9, 'name' => 'Coffee', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Beer', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Boba tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Sushi', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Vegetarian', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Barbecue', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Brunch', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Cocktails', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Pizza', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Whiskey', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Wine', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Foodie', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 9, 'name' => 'Tacos Gin', 'sequence' => '1', 'status' => '1'],

            ['parent_id' => 10, 'name' => 'Coffee', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Beer', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Boba tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Sushi', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Vegetarian', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Barbecue', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Brunch', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Cocktails', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Pizza', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Whiskey', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Wine', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Foodie', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 10, 'name' => 'Tacos Gin', 'sequence' => '1', 'status' => '1'],

            ['parent_id' => 11, 'name' => 'Coffee', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Beer', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Boba tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Sushi', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Vegetarian', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Barbecue', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Brunch', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Cocktails', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Pizza', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Whiskey', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Wine', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Foodie', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Tea', 'sequence' => '1', 'status' => '1'],
            ['parent_id' => 11, 'name' => 'Tacos Gin', 'sequence' => '1', 'status' => '1'],

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intrest_categories');
    }
}
