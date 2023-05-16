<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use MongoDB\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register a new user
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->only('name', 'email', 'password'), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'email_verified_at' => time(),
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    /**
     * Log in and get a JWT token via given credentials
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }        

        $token = auth()->attempt($credentials, true);

        if (!$token) {
            return response()->json([
                'status' => 401,
                'error' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'status' => 200,
            'user' => auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }

    /**
     * Get current logged in user's data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        return response()->json(auth()->user(), 200);
    }

    /**
     * Get a new JWT token for the logged in user
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function refresh()
    {
        return response()->json([
            'access_token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'Succesfully logged out'
        ], 200);
    }
}
