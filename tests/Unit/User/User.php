<?php

namespace Tests\Unit\User;

use App\Models\UsersModel;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DevelomentData;
use Tests\TestCase;

class UserTest extends TestCase {

  use WithFaker;
  /**
   * A basic unit test example.
   *
   * @return void
   */
  public function test_store_user() {
    $payload = [
      'nickname' => $this->faker->nickname,
      'email' => $this->faker->unique()->email,
      'password' => 'password',
      'status' => $this->faker->numberBetween(0, 1)
    ];
    $response = $this->json('POST', '/api/as/auth/register', $payload);
    $response->assertStatus(201);
  }

  public function test_login_user() {
    $user = UsersModel::factory()->create()->email;

    $response = $this->json('POST', '/api/as/auth/login', [
      'email' => $user,
      'password' => 'password',
    ]);

    $response->assertStatus(200);

    $response->assertJsonStructure([
      'access_token',
      'token_type',
      'expires_in',
    ]);
  }

  public function test_get_me() {
    $headers = DevelomentData::headerAuth();
    $response = $this->json('GET', '/api/as/auth/me', [], $headers);

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'id',
      'nickname',
      'email',
      'status',
      'created_at',
      'updated_at',
    ]);
  }

  public function test_refresh_token() {
    $headers = DevelomentData::headerAuth();
    $response = $this->json('GET', '/api/as/auth/refresh', [], $headers);

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'access_token',
      'token_type',
      'expires_in',
    ]);
  }

  public function test_get_users() {
    $headers = DevelomentData::headerAuth();
    $response = $this->json('GET', '/api/as/users', [], $headers);
    $response->assertStatus(200);
  }

  public function test_user_logout() {
    $headers = DevelomentData::headerAuth();
    $response = $this->json('GET', '/api/as/auth/logout', [], $headers);
    $response->assertStatus(200);
  }

}