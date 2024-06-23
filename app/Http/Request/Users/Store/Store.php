<?php

namespace App\Http\Requests\UsersModel;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest {
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize() {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules() {
    return [
      'display_name'      => 'required',
      'display_email'     =>  'required|string|email|max:100|unique:users',
      'password'          =>  'required|string|min:6',
      'status'            =>  'required|in:1,0'
    ];
  }
}
