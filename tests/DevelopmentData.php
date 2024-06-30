<?php

namespace Tests;

use App\Models\UsersModel;
use Illuminate\Support\Facades\Auth;

class DevelomentData {

  static function headerAuth() {
    $user = UsersModel::factory()->create();
    $credentials = [
      'email' => $user->email,
      'password' => 'password'
    ];
    $token = Auth::attempt($credentials);

    return [
      'Authorization' => 'Bearer ' . $token
    ];
  }

}