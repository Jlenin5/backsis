<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;
use App\Models\UsersModel;
use App\Helpers\ResponseJson;
use App\Http\Requests\Users\Login;
use App\Http\Requests\Users\Store;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller {

  use ApiResponser;

  public function __construct() {
    $this->middleware('auth:api', ['except' => ['login', 'register']]);
  }

  public function index() {
    return ResponseJson::success(UsersModel::all());
  }

  public function login(Login $request) {
    if (!$token = Auth::attempt(request(['email', 'password']))) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }
    if(Auth::user()->status == 0){
      return response()->json(['error' => 'Unauthorized'], 401);
    }
    return $this->respondWithToken($token);
  }

  public function logout() {
    Auth::logout();
    return response()->json(['message' => 'Logout exitoso']);
  }

  public function me() {
    // return response()->json(Auth::user()->load('roles.permissions'));
  }

  // public function logout() {
  //   auth()->logout();
  //   return response()->json(['message' => 'Sessión cerrada correctamente']);
  // }

  public function refresh() {
    return $this->respondWithToken(Auth::refresh());
  }

  protected function respondWithToken($token) {
    return response()->json([
      'nickname' => Auth::user()->nickname,
      'email' => Auth::user()->email,
      'avatar' => Auth::user()->employee->avatar,
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => Auth::factory()->getTTL() * 60 * 24 * 30,
      'roles' => Auth::user()->roles->load('permissions'),
    ]);
  }

  public function register(Store $request) {
    if (is_null($request->image) != true)
      $path = $request->image->storeAs('public/users', $request->email . '_' . date('is') . '.jpg');
    else
      $path = '';

    $user = UsersModel::create(array_merge(
      $request->all(),
      ['image' => $path],
      ['password' => bcrypt(($request->password))]
    ));

    return response()->json([
      'message'   => 'Usuario registrado correctamente',
      'user'      => $user
    ], 201);
  }

}