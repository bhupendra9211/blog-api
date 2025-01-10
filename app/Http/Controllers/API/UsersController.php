<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function userRegistration(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            $result = array(
                'status' => false,
                'message' => 'Validation errors occurred',
                'error_message' => $validator->errors()
            );
            return response()->json($result, 400);
        }
        $user = User::create(
            [
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]
        );
        if ($user->id) {
            $result = array('status' => true, 'message' => 'User created Sucessfully', 'data' => $user);
            return response()->json($result, 200); 
        } else {
            $result = array('status' => false, 'message' => 'Sometings went wrong ! ');
            return response()->json($result, 400); 
        }
    }
    public function userLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $result = array(
                'status' => false,
                'message' => 'Validation errors occurred',
                'error_message' => $validator->errors()
            );
            return response()->json($result, 400);
        }
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $result = array(
                'status' => false,
                'message' => 'The provided credentials are incorrect.',
            );
            return response()->json($result, 401);
        }else{
            $token = $user->createToken($user->username);
            $result = array('status' => true, 'message' => 'User Login Sucessfully', 'data' => $user,'token' => $token->plainTextToken);
            return response()->json($result, 200); 
        }
    }
}
