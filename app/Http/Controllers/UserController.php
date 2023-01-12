<?php

namespace App\Http\Controllers;

use App\Models\profileImage;
use App\Models\ProfileVideo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\InterestCategory;
use App\Models\UserMatches;

class UserController extends Controller {

	public function register(Request $request) {

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required',
			'password' => 'required',
		]);

		if ($validator->fails()) {

			$response = array();
			$response['status'] = 0;
			$response['msg'] = "Please Enter a blank fileds.";
			$response['statuscode'] = 400;
			$response['data'] = $validator->errors();

		} else {
			$email = $request->input('email');

			$check = User::where([
				['email', '=', $email],
			])->first();

			if ($check) {
				$response = array();
				$response['status'] = 0;
				$response['msg'] = "Email is already exist please try with another email.";
				$response['statuscode'] = 400;
				return response()->json($response)->header("Content-Type", "application/json");
			} else {

				$user = new User();
				$user->name = $request->name;
				$user->email = $request->email;
				$user->password = Hash::make($request->password);
				$user->save();

				Auth::loginUsingId($user->id);
				$user = $request->user();
				$tokenResult = $user->createToken('Personal Access Token');
				$token = $tokenResult->token;
				$token->save();
				$access_token = $tokenResult->accessToken;
				$response = array();
				$response = successRes("User registration successfully");
				$response['data'] = $user;
				$response['data']['photos'] = $this->getUserPhotos($user->id);
				$response['data']['videos'] = $this->getUserVideos($user->id);
				$response['token_type'] = 'Bearer';
				$response['token'] = $access_token;
			}
		}

		return response()->json($response)->header("Content-Type", "application/json");
	}


	public function getUserPhotos($userId) {
		$query = profileImage::where('user_id',$userId)->select('profile_photos');
		$data = $query->get();
        return $data;
	}
	public function getUserVideos($userId) {
		$query = profileVideo::where('user_id',$userId)->select('profile_video','profile_video_privacy');
		$data = $query->get();
		foreach($data as $key => $value) {
			$data[$key]['profile_video_privacy']="".$value['profile_video_privacy'];
		}
        return $data;
	}
	public function getUserKids($userId) {
		$query = User::where('id',$userId)->select('kids');
		$data = $query->get();
		foreach($data as $key => $value) {
			$data[$key]['kids']="".$value['kids'];
		}
        return $data;
	}
	public function login(Request $request) {
		$rules = array(
			"email" => "required",
			"password" => "required",
		);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return $validator->errors();
		} else {
			$data = [
				'email' => $request->email,
				'password' => $request->password,
			];
			if (auth()->attempt($data)) {
				$user = Auth::user();
				$token = Auth::user()->createToken('Token')->accessToken;

				$response = array();
				$response['status'] = 1;
				$response['msg'] = "User login successfully";
				$response['statuscode'] = 200;
				$response['data'] = $user;
				$response['data']['photos'] = $this->getUserPhotos($user->id);
				$response['data']['videos'] = $this->getUserVideos($user->id);
				$response['token'] = $token;
			} else {
				$response = array();
				$response['msg'] = "Email or Password Incorrect!!!";
				$response['status'] = 0;
				$response['statuscode'] = 401;
			}
		}

		return response()->json($response)->header("Content-Type", "application/json");
	}

	public function googleLogin(Request $request) {
		$validator = Validator::make($request->all(), [
			'token' => ['required'],
		]);

		if ($validator->fails()) {
			$response = array();
			$response['status'] = 0;
			$response['msg'] = "The request could not be understood by the server due to malformed syntax";
			$response['statuscode'] = 400;
			$response['data'] = $validator->errors();
		} else {

			$command = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $request->token;
			$ch = curl_init($command);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$content = curl_exec($ch);
			curl_close($ch);
			$content = json_decode($content, true);

			if (isset($content['error']) && $content['error'] != "") {

				$response = array();
				$response['status'] = 0;
				$response['msg'] = "Invalid oauth2 from Google";
				$response['statuscode'] = 401;
				return response()->json($response)->header('Content-Type', 'application/json');
			} else {

				$email = $content['email'];
				$user = User::where('email', $email)->first();
				// $user = User::where('google_id', $content['id'])->first();

				if ($user) {
					Auth::loginUsingId($user->id);
					$user = $request->user();
					$tokenResult = $user->createToken('Personal Access Token');
					$token = $tokenResult->token;
					$token->save();

					$access_token = $tokenResult->accessToken;
					$response = array();
					$response['status'] = 1;
					$response['statuscode'] = 200;
					$response['msg'] = "Successfully login with social";
					$response['data'] = $user;
					$response['data']['photos'] = $this->getUserPhotos($user->id);
					$response['data']['videos'] = $this->getUserVideos($user->id);
					$response['token_type'] = 'Bearer';
					$response['token'] = $access_token;
				} else {
					$user= new User();
					$user->email = $content['email'];
					$user->name = $content['name'];
					$user->save();

					Auth::loginUsingId($user->id);
					$user = $request->user();
					$tokenResult = $user->createToken('Personal Access Token');
					$token = $tokenResult->token;
					$token->save();
					$access_token = $tokenResult->accessToken;

					$response = array();
					$response['status'] = 1;
					$response['statuscode'] = 201;
					$response['msg'] = "Successfully login with social";
					$response['data'] = $user;
					$response['data']['photos'] = $this->getUserPhotos($user->id);
					$response['data']['videos'] = $this->getUserVideos($user->id);
					$response['token_type'] = 'Bearer';
					$response['access_token'] = $access_token;
				}
			}
		}
		return response()->json($response)->header('Content-Type', 'application/json');

	}

	public function googleRegister(Request $request) {
		$validator = Validator::make($request->all(), [
			'token' => ['required'],
		]);

		if ($validator->fails()) {
			$response = array();
			$response['status'] = 0;
			$response['msg'] = "The request could not be understood by the server due to malformed syntax";
			$response['statuscode'] = 400;
			$response['data'] = $validator->errors();
		} else {

			$command = 'https://oauth2.googleapis.com/tokeninfo?id_token=' .$request->token;
			$ch = curl_init($command);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$content = curl_exec($ch);
			curl_close($ch);
			$content = json_decode($content, true);

			if (isset($content['error']) && $content['error'] != "") {

				$response = array();
				$response['status'] = 0;
				$response['msg'] = "Invalid oauth2 from Google";
				$response['statuscode'] = 401;
				return response()->json($response)->header('Content-Type', 'application/json');
			} else {
				$email = $content['email'];
				$user = User::where('email', $email)->first();

				if ($user) {
					$response = errorRes("Email Already exists, Please try with another email.");
					$response['data']=$validator->errors();
				} else {
					$user = new User();
					$user->name = $content['given_name'];
					$user->email = $content['email'];
					$user->password = '';
					$user->save();

					Auth::loginUsingId($user->id);
					$user = $request->user();
					$tokenResult = $user->createToken('Personal Access Token');
					$token = $tokenResult->token;
					$token->save();
					$access_token = $tokenResult->accessToken;

					$response = array();
					$response['user'] = $user;
					$response['token_type'] = 'Bearer';
					$response['token'] = $access_token;
					$response = successRes("Successfully login with social");
				}
			}
		}
		return response()->json($response)->header('Content-Type', 'application/json');

	}

	public function logOut(Request $request) {
		$token = $request->user()->token();
		$token->revoke();

		$response = array();
		$response = successRes("Logout successfully");
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function userDetails(Request $request) {
		$selectColumns = array(
			'users.id',
			'users.name',
			'users.email',
			'users.age',
			'users.dob',
			'users.gender',
			'users.profession',
			'users.relationship_status',
			'users.height',
			'users.country',
			'users.state',
			'users.city',
			'users.home_location',
			'users.profile_prompts',
			'users.body_type',
			'users.exercise',
			'users.kids',
			'users.relationship_goals',
			'users.ethnicity',
			'users.religion',
			'users.my_interests',
			'users.education',
			'users.preferences_iamlookingfor',
			'users.preferences_max_age',
			'users.preferences_min_age',
			'users.preferences_max_height',
			'users.preferences_min_height',
			'users.preferences_maxdistance',
			'users.preferences_ethnicity',
			'users.preferences_religion',
			'users.preferences_education',
			'users.preferences_relationshipgoals',
			'users.preferences_kids',
			'users.profile_complete',
		);
		$query = User::query();
		$query->select($selectColumns);
		$data = $query->find(Auth::user()->id);
		$user = Auth::user();

		$response = array();
		$response = successRes("List of user details");
		$response['data'] = $data;
		$response['data']['photos'] = $this->getUserPhotos($user->id);
		$response['data']['videos'] = $this->getUserVideos($user->id);
        return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function personalInfo(Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required',
			'gender' => 'required',
			'profession' => 'required',
			'relationship_status' => 'required',
			'height' => 'required',
			'age' => 'required',
			'home_location' => 'required',
			'profile_prompts' => 'required',
			'body_type' => 'required',
			'exercise' => 'required',
			'kids' => 'required',
			'relationship_goals' => 'required',
			'ethnicity' => 'required',
			'religion' => 'required',
			'profile_video' => 'required',
			'my_interests' => 'required',
			'education' => 'required',

		]);
		if ($validator->fails()) {
			$response = errorRes("Please fill the blanks");
			$response['data']=$validator->errors();

		} else {
			$personal = User::find(Auth::user()->id);
			$personal->name = $request->name;
			$personal->email = $request->email;
			$personal->gender = $request->gender;
			$personal->profession = $request->profession;
			$personal->relationship_status = $request->relationship_status;
			$personal->height = $request->height;
			$personal->age = $request->age;
			$personal->home_location = $request->home_location;
			$personal->profile_prompts = $request->profile_prompts;
			$personal->body_type = $request->body_type;
			$personal->exercise = $request->exercise;
			$personal->kids = $request->kids;
			$personal->relationship_goals = $request->relationship_goals;
			$personal->ethnicity = $request->ethnicity;
			$personal->religion = $request->religion;
			if (isset($request->profile_video)) {
				$file_name = date("YmdHis") . rand(100, 900);
				$file = $request->profile_video;
				$extension = $file->getClientOriginalName();

				$post_image = $file_name . "." . $extension;
				$destination_path = public_path() . "/avtarvideos/";
				if (!is_dir($destination_path)) {
					mkdir($destination_path, 0777, true);
				}
				if ($file->move($destination_path, $post_image)) {
					$personal->profile_video = $post_image;
				}

			}

			$personal->profile_video = $post_image;
			$personal->save();

			$personal->my_interests = $request->my_interests;
			$personal->preferences_education = $request->education;
			$personal->update();

			$response = array();
			$response = successRes("Updated Successfully");
			$response['data'] = $personal;
		}

		return response()->json($response)->header("Content-type", 'applocation/json');
	}
	public function savePersonalInfo(Request $request) {
		$personal = User::find(Auth::user()->id);
		$personal->body_type = $request->body_type;
		$personal->exercise = $request->exercise;
		$personal->kids =(int) $request->kids;
		$personal->relationship_goals = $request->relationship_goals;
		$personal->ethnicity = $request->ethnicity;
		$personal->religion = $request->religion;
		$personal->education = $request->education;
		if(Auth::user()->education == ""){
			$personal->profile_complete=$personal->profile_complete+14;
		}
		$personal->save();

		$user = Auth::user();
		$response = array();
		$response = successRes("Saved personal information");
		$response['data'] = $personal;
		$response['data']['photos'] = $this->getUserPhotos($user->id);
		$response['data']['videos'] = $this->getUserVideos($user->id);

		return response()->json($response)->header("Content-type", 'applocation/json');
	}
	public function savePersonalBasicInfo(Request $request){
		$personal = User::find(Auth::user()->id);
		$personal->name = $request->name;
		$personal->gender = $request->gender;
		$personal->profession =$request->profession;
		$personal->relationship_status = $request->relationship_status;
		$personal->height = (float)$request->height;
		$personal->dob = $request->dob;
		$personal->age = (int)$request->age;

		if(Auth::user()->dob == ""){
			$personal->profile_complete = $personal->profile_complete + 14;
		}
		$personal->save();	

		$user = Auth::user();
		$response = array();
		$response = successRes("Saved personal basic information");
		$response['data'] = $personal;
		$response['data']['photos'] = $this->getUserPhotos($user->id);
		$response['data']['videos'] = $this->getUserVideos($user->id);
		return response()->json($response)->header("Content-type", 'applocation/json');
	}
	public function savePersonalBasicPref(Request $request){
		$personal = User::find(Auth::user()->id);
		$personal->preferences_iamlookingfor = $request->looking_for;
		$personal->	preferences_max_age = (int)$request->max_age;
		$personal->	preferences_min_age = (int)$request->min_age;
		$personal->preferences_max_height = (float)$request->max_height;
		$personal->preferences_min_height =(float)$request->min_height;
		$personal->preferences_maxdistance = $request->max_distance;
		$personal->preferences_ethnicity = $request->ethnicity;
		$personal->preferences_religion = $request->religion;
		$personal->preferences_education = $request->education;
		$personal->preferences_relationshipgoals = $request->relationshipgoals;
		$personal->preferences_kids = (int) $request->kids;
		$personal->save();

		$user = Auth::user();
		$response = array();
		$response = successRes("Saved Personal Basic Preferences");
		$response['data'] = $personal;
		$response['data']['photos'] = $this->getUserPhotos($user->id);
		$response['data']['videos'] = $this->getUserVideos($user->id);

		return response()->json($response)->header("Content-type", 'applocation/json');
	}
	public function saveUserLocation(Request $request) {
		$id = Auth::user()->id;
		$user = User::find($id);
		$user->country = $request->country;
		$user->state = $request->state;
		$user->city = $request->city;
		if(Auth::user()->country == ""){
			$user->profile_complete = $user->profile_complete + 14;
		}
		$user->save();

		$response = array();
		$response = successRes("Saved user location");
		$response['data'] = $user;
		$response['data']['photos'] = $this->getUserPhotos($user->id);
		$response['data']['videos'] = $this->getUserVideos($user->id);

		return response()->json($response)->header("Content-type", 'applocation/json');
	}

	public function saveProfileVideo(Request $request) {
		$validator = Validator::make($request->all(), [
			'profile_video' => 'required',
			'profile_video_privacy' => 'required',
		]);

		if ($validator->fails()) {
			$response = errorRes("User video is required");
			$response['data'] = $validator->errors();

		} else {
			$ifExists = profileVideo::where('user_id', Auth::user()->id)->first();
			if(! $ifExists){
				$user = User::find(Auth::user()->id);
				$user->profile_complete=$user->profile_complete+14;
				$user->save();
			}
			$profileVideo = new profileVideo();
			$profileVideo->profile_video = $request->profile_video;
			$profileVideo->profile_video_privacy =$request->profile_video_privacy;
			$profileVideo->user_id = Auth::user()->id;
			$profileVideo->save();

			$response = array();
			$user = Auth::user();
			$response = successRes("Video Uploded successfully");
			$response['data'] = $user;
			$response['data']['photos'] = $this->getUserPhotos($user->id);
			$response['data']['videos'] = $this->getUserVideos($user->id);

			return response()->json($response)->header('Content-Type', 'application/json');
		}
	}

	public function saveProfileImage(Request $request) {
		$validator = Validator::make($request->all(), [
			'profile_photos' => 'required',
		]);

		if ($validator->fails()) {
			$response = errorRes("Image is Required");
			$response['data'] = $validator->errors();
		} else {
			$ifExists = profileImage::where('user_id', Auth::user()->id)->first();
			if(! $ifExists){
				$user = User::find(Auth::user()->id);
				$user->profile_complete=$user->profile_complete+14;
				$user->save();
			}
			$profilePhoto = new profileImage();
			$profilePhoto->profile_photos = $request->profile_photos;
			$profilePhoto->profile_photos_privacy = $request->profile_photos_privacy;
			$profilePhoto->user_id = Auth::user()->id;
			$profilePhoto->save();


			$response = array();
			$response = successRes("Image Uploded successfully");
			$user = Auth::user();
			$response['data'] = $user;
			$response['data']['photos'] = $this->getUserPhotos($user->id);
			$response['data']['videos'] = $this->getUserVideos($user->id);
			return response()->json($response)->header('Content-Type', 'application/json');
		}
	}

	public function searchUser(Request $request) {
		if ($request->seach_value) {
			$search = User::where("name", "like", "%" . $request->search_value . "%")
				->where('id', '!=', Auth::user()->id)
				->where("email", "like", "%" . $request->search_value . "%")
				->where("age", "like", "%" . $request->search_value . "%")
				->paginate(15);

			if (count($search) > 0) {
				$response = array();
				$response['data'] = $search;
				$response = successRes("user Search Successfully");

			} else {
				$response = errorRes("data not found");
			}

		} else {
			$search = User::orderBy('id', 'desc')->limit(10)->paginate();

			$response = array();
			$response = successRes("user Search Successfully");
			$response['data'] = $search;
		}
		return response()->json($response)->header("Content-type", "application'json");

	}

	public function myPreferences(Request $request) {
		$validator = Validator::make($request->all(), [
			'preferences_iamlookingfor' => 'required',
			'preferences_age' => 'required',
			'preferences_height' => 'required',
			'preferences_maxdistance' => 'required',
			'preferences_ethnicity' => 'required',
			'preferences_religion' => 'required',
			'preferences_education' => 'required',
			'preferences_relationshipgoals' => 'required',
			'preferences_kids' => 'required',

		]);
		if ($validator->fails()) {
			$response = errorRes("you can't leave it blank");
			$response['data'] = $validator->errors();

		} else {

			$myPreferences = User::find(Auth::user()->id);
			$myPreferences->preferences_iamlookingfor = $request->preferences_iamlookingfor;
			$myPreferences->preferences_age = $request->preferences_age;
			$myPreferences->preferences_height = $request->preferences_height;
			$myPreferences->preferences_maxdistance = $request->preferences_maxdistance;
			$myPreferences->preferences_ethnicity = $request->preferences_ethnicity;
			$myPreferences->preferences_religion = $request->preferences_religion;
			$myPreferences->preferences_education = $request->preferences_education;
			$myPreferences->preferences_relationshipgoals = $request->preferences_relationshipgoals;
			$myPreferences->preferences_kids = $request->preferences_kids;
			$myPreferences->update();

			$response = array();
			$response = successRes("Updated Successfully");
			$response['data'] = $myPreferences;
		}

		return response()->json($response)->header("Content-type", 'applocation/json');

	}
	public function preferencesList(Request $request) {

		$preferencesList = User::select('preferences_iamlookingfor', 'preferences_age', 'preferences_height', 'preferences_maxdistance', 'preferences_ethnicity', 'preferences_religion', 'preferences_education', 'preferences_relationshipgoals', 'preferences_kids')
			->where('id', Auth::user()->id)
			->get();

		$response = array();
		$response = successRes("List of preferences");
		$response['data'] = $preferencesList;
		return response()->json($response)->header("Content-Type", "application'json");
	}

	public function basicInfoEdit(Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'gender' => 'required',
			'profession' => 'required',
			'relationship_status' => 'required',
			'height' => 'required',
			'age' => 'required',
			'current_location' => 'required',
			'home_location' => 'required',
		]);
		if ($validator->fails()) {
			$response = errorRes("you can't leave it blank");
			$response['data'] = $validator->errors();
		} else {

			$myPreferences = User::find(Auth::user()->id);
			$myPreferences->name = $request->name;
			$myPreferences->gender = $request->gender;
			$myPreferences->profession = $request->profession;
			$myPreferences->relationship_status = $request->relationship_status;
			$myPreferences->height = $request->height;
			$myPreferences->age = $request->age;
			$myPreferences->current_location = $request->current_location;
			$myPreferences->home_location = $request->home_location;
			$myPreferences->update();

			$response = array();
			$response = successRes("Besic Information Updated Successfully");
			$response['data'] = $myPreferences;
		}

		return response()->json($response)->header("Content-type", 'applocation/json');

	}

	public function promptQuestions(Request $request) {
		$profilePrompts = User::select('profile_prompts')
			->where('id', Auth::user()->id)
			->get();

		$response = array();
		$response = successRes("List of prompt questions");
		$response['data'] = $profilePrompts;
		return response()->json($response)->header("Content-Type", "application'json");
	}

	public function someMoreInfo(Request $request) {
		$validator = Validator::make($request->all(), [
			'body_type' => 'required',
			'exercise' => 'required',
			'kids' => 'required',
			'relationship_goals' => 'required',
			'ethnicity' => 'required',
			'religion' => 'required',
		]);
		if ($validator->fails()) {
			$response = errorRes("Please fill the blanks");
			$response['data'] = $validator->errors();

		} else {

			$myPreferences = User::find(Auth::user()->id);
			$myPreferences->body_type = $request->body_type;
			$myPreferences->exercise = $request->exercise;
			$myPreferences->kids = $request->kids;
			$myPreferences->relationship_goals = $request->relationship_goals;
			$myPreferences->ethnicity = $request->ethnicity;
			$myPreferences->religion = $request->religion;
			$myPreferences->update();

			$response = array();
			$response = successRes("Some More Info Updated Successfully");
			$response['data'] = $myPreferences;
		}

		return response()->json($response)->header("Content-type", 'applocation/json');
	}

	//- forgot password
	public function forgotPassword(Request $request){
		$validator = Validator::make($request->all(), [
			"email" => "required",
		]);
		if ($validator->fails()) {
			$response = array();
			$response = errorRes("Please fill the blanks");
			$response['data'] = $validator->errors();
		}else{
			if (!User::where('email', '=', $request->email)->first()) {
				$response = array();
				$response = errorRes("Email is not exists!");
			}else{
				$user = User::where('email', $request->email)->first();
				$emailOtp = "1234";
				$user->email_otp = $emailOtp;
				$user->save();

				$data = array();
				$response = successRes("OTP sent successfully");
			}
		}
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	//- forgot password email verification
	public function otpVerify(Request $request){
		$validator = Validator::make($request->all(), [
			"otp" => "required",
			"email" => "required",
			"password" => "required",
		]);
		if ($validator->fails()) {
			$response = array();
			$response = errorRes("Please fill the blanks");
			$response['data'] = $validator->errors();
		}else{
			$user = User::where('email', $request->email)->where('email_otp', $request->otp)->first();
			if($user){
				$user->password = Hash::make($request->password);
				$user->email_otp = null;
				$user->save();
				$response = array();
				$response = successRes("Password update successfully");
			}else{
				$response = errorRes("OTP and Email mismatch");
			}
		}
		return response()->json($response)->header('Content-Type', 'application/json');
	}
	public function userMatches1(){

		$InterestCategory=InterestCategory::where('parent_id','!=',0)->get();
		$matchQuestyArray=[];
		foreach($InterestCategory as $IC=>$ICV){
			$matchQuestyArray[]="IF(`inst_".$ICV->id."` LIKE ". Auth::user()["inst_".$ICV->id].", 1, 0)";
		}

		$matchQuestyArray=implode("+",$matchQuestyArray);
		$matchQuestyArray="(((".$matchQuestyArray.")/".count($InterestCategory).")*100)";
		$userImage = profileImage::where('user_id',)->select('profile_photos');
		
		$query = User::query();
		$query->select(DB::raw($matchQuestyArray." as match_percentage, id, name, email, dob"));
		$query->where('profile_complete','>=',100);
		$query->where('id','!=',Auth::user()->id);
		$query->orderby('match_percentage','desc');
		$data=$query->paginate(10);
		
		$singleRecord = [];
		$data = $query->get();
		foreach($data as $key => $value){
			$singleRecord[] = $value;
			$value['image'] = profileImage::where('user_id',$value['id'])->select('profile_photos')->get();
		}
		$response = array();
		$response = successRes("User Matches List");
		$response['data'] = $singleRecord;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function userMatches()
	{
		$query = User::query();
		$query->select(DB::raw("id, name, email, dob,age"));
		$query->where('profile_complete','>=',100);
		$query->where('id','!=',Auth::user()->id);

		if(Auth::user()->preferences_iamlookingfor){
			$query->where('gender',Auth::user()->preferences_iamlookingfor);
		}
		if(Auth::user()->preferences_max_height && Auth::user()->preferences_min_height){
			$query->where(function($query2){
				$query2->where('height','>=',Auth::user()->preferences_min_height);
				$query2->where('height','<=',Auth::user()->preferences_max_height);
			});
		}
		if(Auth::user()->preferences_max_age && Auth::user()->preferences_min_age){
			$query->where(function($query2){
				$query2->where('age','>=',Auth::user()->preferences_min_age);
				$query2->where('age','<=',Auth::user()->preferences_max_age);
			});
		}
		if(Auth::user()->preferences_ethnicity){
			$query->where('ethnicity',Auth::user()->preferences_ethnicity);
		}
		if(Auth::user()->preferences_religion){
			$query->where('religion',Auth::user()->preferences_religion);
		}
		if(Auth::user()->preferences_education){
			$query->where('education',Auth::user()->preferences_education);
		}
		if(Auth::user()->preferences_relationshipgoals){
			$query->where('relationship_goals',Auth::user()->preferences_relationshipgoals);
		}
		if(Auth::user()->preferences_kids){
			$query->where('kids',Auth::user()->preferences_kids);
		}
		$query->orderBy('id','desc');
		$data = $query->paginate(10);

		foreach($data as $key => $value){
			$value['image'] = profileImage::where('user_id',$value['id'])->select('profile_photos')->get();
		}
		$response = array();
		$response = successRes("User Matches List");
		$response['data'] = $data;
		return response()->json($response)->header('Content-Type', 'application/json');
	}
	public function userMatchRecord(Request $request){
		$validator = Validator::make($request->all(), [
			"to_user_id" => "required",
		]);
		if ($validator->fails()) {
			$response = array();
			$response = errorRes("Please fill the blanks");
			$response['data'] = $validator->errors();
		}else{
			$data = UserMatches::where('to_user_id',$request->to_user_id)->where('from_user_id',Auth::user()->id)->first();
			if(!$data){
				$data = new UserMatches();
				$data->from_user_id = Auth::user()->id;
				$data->to_user_id = (int)$request->to_user_id;
				$data->save();

				$response = array();
				$response = successRes("List of Users Matches");
				$response['data'] = $data; 
			}else{

				$InterestCategory=InterestCategory::where('parent_id','!=',0)->get();
				$to_user = User::find($request->to_user_id);

				$matchQuestyArray=[];
				foreach($InterestCategory as $IC=>$ICV){
					$matchQuestyArray[]="IF(`".$ICV->id." LIKE ". Auth::user()["inst_".$ICV->id].", 1, 0)";
				}

				$matchQuestyArray=implode("+",$matchQuestyArray);
				$response = array();
				$response = successRes("User exists");
				$response['data'] = 12;

			}
		}
		return response()->json($response)->header('Content-Type', 'application/json');
	}
}
