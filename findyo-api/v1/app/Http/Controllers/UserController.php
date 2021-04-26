<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserCredentials;
use Validator;

class UserController extends Controller
{
    public function emailregister(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|min:8|same:password'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 202);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $userData = new User;
        $userData->email = $input['email'];
        $userData->password = $input['password'];
        $userData->user_role_id = 1;
        $userData->save();

        $resArr = [];
        $resArr['token'] =  $userData->createToken('api-application')->accessToken;
        $resArr['name'] =  $userData->username;
        $resArr['userrole'] =  $userData->user_role_id;

        return response()->json($resArr, 200);
    }

    public function emaillogin(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $resArr = [];
            $resArr['token'] =  $user->createToken('api-application', $user->user_role_id == 1 ? ['user-role-admin'] : ['user-role-user'])->accessToken;
            $resArr['name'] =  $user->username;
            $resArr['userrole'] = $user->user_role_id == 1 ? 'Admin' : 'User';

            return response()->json($resArr, 200);
        } else {
            return response()->json(['error' => 'Unauthenticated'], 203);
        }
    }

    public function emailtest(Request $request)
    {
        return response()->json('Route hitted', 200);
    }
    public function emailadmin(Request $request)
    {
        return response()->json('You are an admin', 200);
    }
}
