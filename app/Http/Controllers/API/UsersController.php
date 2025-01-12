<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    // public function test(){
    //     $result = array('status' => true, 'message' => 'User created Sucessfully');
    //     return response()->json($result, 200); 
    // }
    public function userRegistration(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while registration.',
            ], 500);
        }
    }
    public function userLogin(Request $request)
    {
        try {
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
            } else {
                $token = $user->createToken($user->username);
                $result = array('status' => true, 'message' => 'User Login Sucessfully', 'data' => $user, 'token' => $token->plainTextToken);
                return response()->json($result, 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while Login Users.',
            ], 500);
        }
    }
    public function getProfile(Request $request)
    {
        try {
            $user = $request->user();
            return response()->json([
                'status' => true,
                'message' => 'User profile fetched successfully.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the profile.',
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation errors occurred.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $user->update($request->only(['username', 'email']));

            return response()->json([
                'status' => true,
                'message' => 'User profile updated successfully.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the profile.',
            ], 500);
        }
    }
}
