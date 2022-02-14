<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function login(Request $request){
        $input = $request->only(['email','password']);
        $rules = [
            'email'         => 'required|email',
            'password'      => 'required|min:6'
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()){
            return $this->sendError($validator->errors(),Response::HTTP_BAD_REQUEST);
        }

        $credentials = [
            'email'     =>  $input['email'],
            'password'  =>  $input['password']
        ];

        if (Auth::attempt($credentials)){
            $user = Auth::user();
            $user->api_token = $user->createToken('LoanApp')->accessToken;

            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['token'] = $user->api_token;

            return $this->sendResponse(__('message.user.login.success'), $data);
        }else{
            return $this->sendError(__('message.user.login.error'),Response::HTTP_UNAUTHORIZED);
        }
    }
}
