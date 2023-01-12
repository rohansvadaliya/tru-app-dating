<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\InterestCategory;
use App\Models\profileImage;
use App\Models\ProfileVideo;
use App\Models\Question;
use App\Models\User;
use App\Models\UserInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class profileImageController extends Controller {
	public function getUserPhotos($userId) {
		$query = profileImage::where('user_id',$userId)->select('profile_photos');
		$data = $query->get();
        return $data;
	}
	public function getUserVideos($userId) {
		$query = ProfileVideo::where('user_id',$userId)->select('profile_video','profile_video_privacy');
		$data = $query->get();
		foreach($data as $key => $value) {
			$data[$key]['profile_video_privacy']="".$value['profile_video_privacy'];
		}
        return $data;
	}
	
	public function distroyProfileImage(Request $request) {
		$validator = validator::make($request->all(), [
			'id' => 'required',
		]);
		if ($validator->fails()) {
			$response = array();
			$response = errorRes("The The request could not be understood by the server due to malformed syntax");
			$response['data'] = $validator->errors();
		} else {

			$profile = profileImage::where('id', $request->id)->where('user_id', Auth::user()->id)->first();
			if ($profile) {
				$profileimage = profileImage::where('id', $request->id);
				foreach ($profileimage as $key => $value) {
					$profileimage = profileImage::find($value->id);
					$profileimage->delete();
				}
				$profile->delete();
				$response = array();
				$response = successRes("Profile Image Deleted Successfully");
			} else {
				$response = array();
				$response = errorRes("Invalid Image Id");
			}

		}
		return response()->json($response)->header('Content-Type', 'application/json');

	}
	public function index(){
		$selectColumns = array(
			'questions.id','questions.name'
		);
		$query = Question::query();
		$query->select($selectColumns);
		$data = $query->get();
		
		$response = array();
		$response = successRes("Questions list");
		$response['data'] = $data;
		
        return response()->json($response)->header('Content-Type', 'application/json');
	}
	public function saveQuestionAnswers(Request $request){
		$validator = validator::make($request->all(), [
			'request_params' => 'required',
		]);
		if ($validator->fails()) {
			$response = errorRes("Please send valid request");
			$response['data']=$validator->errors();
		} else {
			$requestParams=$request->request_params;
            $requestParams=json_decode($requestParams, true);
			foreach($requestParams as $key => $value) {
				$isAnswerAvaialble=Answer::where('user_id', Auth::user()->id)->where('question_id',$value['question_id'])->first();
				if($isAnswerAvaialble){
					$isAnswerAvaialble->user_id=Auth::user()->id;
					$isAnswerAvaialble->question_id=$value['question_id'];
					$isAnswerAvaialble->answer=$value['answer'];
					$isAnswerAvaialble->save();
				}else{
					$ifExists = Answer::where('user_id', Auth::user()->id)->first();
					if(! $ifExists){
						$user = User::find(Auth::user()->id);
						$user->profile_complete=$user->profile_complete+14;
						$user->save();
					}

					$Answer = new Answer();
					$Answer->user_id=Auth::user()->id;
					$Answer->question_id=$value['question_id'];
					$Answer->answer=$value['answer'];
					$Answer->save();
				}
			}
			$answerRecord = Answer::select('id','user_id', 'question_id','answer')->where('user_id', Auth::user()->id)->get();
			$response = array();
			$response = successRes("Record saved successfully");
			$response['data'] = $answerRecord;
			return response()->json($response)->header('Content-Type', 'application/json');
		}
	}
	public function listQuestionAnswers(Request $request){
		$selectColumns = array(
			'question_answers.id',
			'question_answers.user_id',
			'questions.name as question_id',
			'question_answers.answer',
		);
		$query = Answer::query();
		$query->select($selectColumns);
		$query->where('user_id', Auth::user()->id);
		$query->leftJoin('questions','questions.id','=','question_answers.question_id');
		$data = $query->get();

		$response = array();
		$response = successRes("Question-Answer list");
		$response['data'] = $data;
        return response()->json($response)->header('Content-Type', 'application/json');
	}
	public function interestCategories(){
		$data = InterestCategory::with('child_category')->where('parent_id',0)->select('id', 'name')->get();

		$response = array();
		$response = successRes("Interest Categories List");
		$response['data'] = $data;
        return response()->json($response)->header('Content-Type', 'application/json');
	}
	public function saveInterestCategories(Request $request){
		$validator = validator::make($request->all(), [
			'subcategory_ids' => 'required',
		]);
		if ($validator->fails()) {
			$response = errorRes("Please send valid request");
			$response['data'] = $validator->errors();
		} else {

			$InterestCategory=InterestCategory::where('parent_id','!=',0)->get();
			UserInterest::where('user_id', Auth::user()->id)->delete();
			foreach($InterestCategory as $IC=>$ICV){
				Auth::user()['inst_'.$ICV->id]=0;
			}
			Auth::user()->save();
			// store record in users and user interest
			$startDate = explode(",", $request->subcategory_ids);
			foreach($startDate as $key => $value) {
				$ifExists = UserInterest::where('user_id', Auth::user()->id)->first();
				if(! $ifExists){
					$user = User::find(Auth::user()->id);
					$user->profile_complete=100;
					$user->update();
				}
				
				$getId = InterestCategory::where('id',$value)->first();
				$Category = new UserInterest();
				$Category->user_id = Auth::user()->id;
				$Category->category_id = $getId->parent_id;
				$Category->subcategory_id = $value;
				$Category->save();
				Auth::user()['inst_'.$value]=1;
			}
			Auth::user()->save();

			$data = UserInterest::select('id','user_id', 'category_id','subcategory_id')->where('user_id', Auth::user()->id)->get();
			$response = array();
			$response = successRes("Record saved successfully");
			$response['data'] = $data;
			return response()->json($response)->header('Content-Type', 'application/json');
		}
	}
	public function listSaveInterestCategories(){
		$selectColumns = array(
			'user_interests.id',
			'user_interests.user_id',
			'user_interests.category_id',
			'user_interests.subcategory_id',
			'interest_categories.name',
		);
		$query = UserInterest::query();
		$query->select($selectColumns);
		$query->where('user_id', Auth::user()->id);
		$query->leftJoin('interest_categories','interest_categories.id','=','user_interests.subcategory_id');
		$data = $query->get();

		$response = array();
		$response = successRes("List of Saved Interest Categories");
		$response['data'] = $data;
		return response()->json($response)->header('Content-Type', 'application/json');
	}
}
