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
        $resArr['cid'] = $userData->cid;

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
            $resArr['cid'] = $user->cid;

            return response()->json($resArr, 200);
        } else {
            return response()->json(['error' => 'Unauthenticated'], 203);
        }
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $intrests
     * @return \Illuminate\Http\Response
     */
    public function updateuser(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\User,id'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $user  = User::find($request['id']);
        $user->username = $request['username'];
        $user->displayname = $request['displayname'];
        $user->firstname = $request['firstname'];
        $user->lastname = $request['lastname'];
        $user->address = $request['address'];
        $user->about = $request['about'];
        $user->phone = $request['phone'];
        $user->save();

        return response()->json($user, 200);
    }

       /**
     * This will be returned the location with it's location level.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function GetUserByUserId($id)
    {
        if (User::where('id', $id)->exists()) {
            $user = User::where('id', $id)->get();
            return response($user, 200);
        } else {
            return response()->json([
                "message" => "user not found"
            ], 404);
        }
    }

    public function index()
    {
        $post = User::paginate(15)->appends(['sort' => 'id']);
        return response()->json($post, 200);
    }

    
}
