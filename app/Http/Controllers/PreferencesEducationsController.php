<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\preferencesEducation;

class preferencesEducationsController extends Controller
{
    public function preferencesEducation(Request $request)
    {
        $preferencesEducation = preferencesEducation::select('select_education')
        ->where('user_id',Auth::user()->id)
        ->get();

            $response= array();
            $response['status']=1;
            $response['statusCode']=200;
            $response['data']= $preferencesEducation;
            return response()->json($response)->header("Content-Type","application'json");
       
        }

}

