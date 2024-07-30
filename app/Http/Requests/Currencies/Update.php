<?php

namespace App\Http\Requests\Currencies;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest {

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
      'name'       =>  'required|string|max:30',
      'code'       =>  'required|string|max:4',
      'symbol'     =>  'required|string|max:3',
      'status'     =>  'required|in:1,0'
    ];
  }
  
}