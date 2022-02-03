<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request){
// validation
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "phone_no" => "required",
            "password" => "required|confirmed"
        ]);

        // create user data + save
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_no = $request->phone_no;
        $user->password = bcrypt($request->password);

        $user->save();

        // send response
        return response()->json([
            "status" => 1,
            "message" => "User registered successfully"
        ], 200);
    }
    public function login(Request $request){
        // validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // verify user + token
        if (!$token = auth()->attempt(["email" => $request->email, "password" => $request->password])) {

            return response()->json([
                "status" => 0,
                "message" => "Invalid credentials"
            ]);
        }

        // send response
        return response()->json([
            "status" => 1,
            "message" => "Logged in successfully",
            "access_token" => $token
        ]);
    }

    public function profile(){
    $user_auth = auth()->user();
    return response()->json([
        "status"=>1,
        "message"=>"User profile data",
        "data"=>$user_auth
    ]);
    }
    public function logout(){
        auth()->logout();
        return response()->json([
           "status"=>1,
           "message"=>"user logged out"
        ]);
    }
}
