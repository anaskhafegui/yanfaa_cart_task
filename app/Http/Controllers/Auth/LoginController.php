<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PrivateUserResource;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
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
}
