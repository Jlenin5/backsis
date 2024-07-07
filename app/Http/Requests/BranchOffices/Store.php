<?php

namespace App\Http\Requests\BranchOffices;

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
      'name'              =>  'required|string|max:40',
      'phone'             =>  'required|string|max:9',
      'email'             =>  'required|string|max:50',
      'address'           =>  'required|string|max:60',
      'status'            =>  'required|in:1,0',
    ];
  }
  
}