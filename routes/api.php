<?php

use App\Http\Controllers\PreferencesEducationsController;
use App\Http\Controllers\ProfileImageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::post('register', [UserController::class, "register"]);

Route::post('login', [UserController::class, "login"])->name('login');
Route::post('google-login', [UserController::class, "googleLogin"]);
Route::post('google-register', [UserController::class, "googleRegister"]);
Route::post('forgot-password',[UserController::class,'forgotPassword']);
Route::post('otp-verify',[UserController::class,'otpVerify']);

Route::middleware('auth:api')->group(function () {

	Route::post('log-out', [UserController::class, "logOut"]);
	Route::get('user-details', [UserController::class, "userDetails"]);
	Route::get('personal-information', [UserController::class, "personalInfo"]);
	Route::post('personal-info', [UserController::class, "personalInfo"]);
	Route::post('save-profile-video', [UserController::class, "saveProfileVideo"]);
	Route::post('save-profile-image', [UserController::class, "saveProfileImage"]);
	Route::post('save-user-location', [UserController::class, "saveUserLocation"]);
	Route::post('save-personal-info', [UserController::class, "savePersonalInfo"]);
	Route::post('save-personal-basic-info', [UserController::class, "savePersonalBasicInfo"]);
	Route::post('save-personal-basic-pref', [UserController::class, "savePersonalBasicPref"]);
	Route::get('user-matches', [UserController::class, "userMatches"]);
	Route::post('user-match-record', [UserController::class, "userMatchRecord"]);

	
	// Q & A
	Route::get('questions', [ProfileImageController::class, "index"]);
	Route::post('question-answers', [ProfileImageController::class, "saveQuestionAnswers"]);
	Route::get('list-question-answers', [ProfileImageController::class, "listQuestionAnswers"]);

	// Intrest Category
	Route::get('interest-categories', [ProfileImageController::class, "interestCategories"]);
	Route::post('save-interest-categories', [ProfileImageController::class, "saveInterestCategories"]);
	Route::get('list-save-interest-categories', [ProfileImageController::class, "listSaveInterestCategories"]);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

// Route::get('search-user', [UserController::class, "searchUser"]);
// Route::post('some_more_info', [UserController::class, 'someMoreInfo']);
// Route::post('basic_info_edit', [UserController::class, 'basicInfoEdit']);
// Route::get('prompt_questions', [UserController::class, 'promptQuestions']);
// Route::get('preferences-list', [UserController::class, 'preferencesList']);
// Route::get('like', [UserLikeController::class, "Like"]);
// Route::get('unlike', [UserLikeController::class, "Unlike"]);
// Route::get('likes', [UserLikeController::class, 'likesReciveSent']);
// Route::get('distroy-profile-image', [ProfileImageController::class, "distroyProfileImage"]);

// Route::get('preferences-education', [PreferencesEducationsController::class, 'preferencesEducation']);
