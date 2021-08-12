<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        // Validate data
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        $messages = $validator->getMessageBag();

        //Send failed response if request is not valid
        if($validator->fails()){
            return response()->json(['error' => $validator->$messages()],200);
        }

        // request is valid, create new user

        $user = User::create([
            'name'=> $request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);

        // user created, return success response
        return response()->json([
            'success' => true,
            'message' => "User created successfully",
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // valid credential
        $validator = Validator::make($credentials,[
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);
    }
}