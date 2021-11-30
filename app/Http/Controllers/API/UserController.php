<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(){
        if(Auth::attempt(['tell' => request('tell'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('PsrsPendarNahad-CRM')-> accessToken;
            return response()->json(['success' => $success], $this-> successStatus);
        }

        return response()->json(['error'=>'Unauthorised'], 401);
    }

    /**
     * Register api
     *
     * @param Request $request
     */
    public function register(Request $request)
    {
/*        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        $success['name'] =  $user->name;
        return response()->json(['success'=>$success], $this-> successStatus);*/
    }
}


