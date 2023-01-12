<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\userLike;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class userLikeController extends Controller
{
    public function Like(Request $request){

        $validator = Validator::make($request->all(),[
			'to_user_id' => 'required',
		]);
		if($validator->fails()){
			$response = errorRes("The request could not be understood by the server due to malformed syntax");
			$response['data'] = $validator->errors();
		}else{

            $post = userLike::find($request->to_user_id);

                if($request->to_user_id){

                    $Like = new userLike();
                    $Like->from_user_id = Auth::user()->id;
                    $Like->to_user_id = $request->to_user_id;
                    $Like->save();

                    $response = array();
                    $response = successRes("Post liked successfully!!");
            }else{
                $response = array();
                $response = errorRes("Invalid user id");
            }
        }
		return response()->json($response)->header('Content-Type', 'application/json');
    }


    public function Unlike(Request $request){

        $validator = Validator::make($request->all(),[
			'to_user_id' => 'required',
		]);
		if($validator->fails()){
			$response = errorRes("The request could not be understood by the server due to malformed syntax");
			$response['data'] = $validator->errors();
		}else{
            $feed = userLike::find($request->to_user_id);
            if($request->to_user_id){
                $feedLike = userLike::where('to_user_id',$request->to_user_id)->where('from_user_id',Auth::user()->id)->first();
                if($feedLike){
                    $feedLike->delete();

                    $response = array();
					$response = successRes("Successfully unliked post");
            }else{
                $response = array();
                $response = errorRes("Invalid feed id");
            }   
        }
		return response()->json($response)->header('Content-Type', 'application/json');
        }
    }

    public function likesReciveSent(Request $request)
    {
        $Likerecived = userLike::where('from_user_id',Auth::user()->id)->orderBy('id','DESC')->limit(10)
        ->paginate();

        $LikeSent = userLike::where('to_user_id',Auth::user()->id)->orderBy('id','DESC')->limit(10)
        ->paginate();

        $response = array();
        $response = successRes("Like received successfully");
        $response['datarecived']=$Likerecived;
        $response['datasent']=$LikeSent;
        return response()->json($response)->header("Content-Type","application'json");

    }

}