<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        //validate post request
        $validate = Validator::make($request->all(), [
            'name'      => 'required|string|max:250',
            'email'     => 'required|string|email:rfc,dns|max:250|unique:users,email',
            'password'  => 'required|string|min:6'
        ]);
        if($validate->fails()){
            return $this->response(400, $validate->messages());
        }

        //create new user
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ]);

        $data['token'] = $user->createToken($request->email)->plainTextToken;
        $data['user'] = $user;

        return $this->response(200, $data);
    }

    public function login(Request $request)
    {
        //validate post request
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if($validate->fails()){
            return $this->response(400, $validate->messages());
        }

        // Check email exist
        $user = User::where('email', $request->email)->first();

        // Check password
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $data['token'] = $user->createToken($request->email)->plainTextToken;
        $data['user'] = $user;

        return $this->response(200, $data);
    }

    public function logout(Request $request)
    {
        //remove user logged token
        auth()->user()->tokens()->delete();
        return $this->response(200);
    }
}
