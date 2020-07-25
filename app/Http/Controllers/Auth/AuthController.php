<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PrivateUserResource;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;


class AuthController extends Controller
{
        /**
      * Create a new AuthController instance.
      *
      * @return void
      */
      public function __construct()
      {
         $this->middleware('auth:api', ['except' => ['login','register']]);
      }

      public function login(LoginRequest $request)
      {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
          return response()->json([
            'errors' => [
              'email' => ['Could not sign you in with those details.']
            ]
          ], 422);
        }

        return (new PrivateUserResource($request->user()))
          ->additional([
            'meta' => [
              'token' => $this->respondWithToken($token)
            ]
          ]);
      }

      public function register(RegisterRequest $request)
      {
        $user = User::create(
          $request->only('email', 'name', 'password')
        );

        return new PrivateUserResource($user);
      }

      public function show(Request $request)
      {
        return new PrivateUserResource($request->user());
      }

      public function logout()
      {
         $this->guard()->logout();

         return response()->json(['message' => 'Successfully logged out']);
     }

     /**
      * Refresh a token.
      *
      * @return \Illuminate\Http\JsonResponse
      */
     public function refresh()
     {
         return $this->respondWithToken($this->guard()->refresh());
     }

     /**
      * Get the token array structure.
      *
      * @param  string $token
      *
      * @return \Illuminate\Http\JsonResponse
      */
     protected function respondWithToken($token)
     {
         return response()->json([
             'access_token' => $token,
             'token_type' => 'bearer',
             'expires_in' => $this->guard()->factory()->getTTL() * 60
         ]);
     }

      /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
