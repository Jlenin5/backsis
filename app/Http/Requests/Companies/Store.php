<?php

namespace App\Http\Requests\Companies;

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
      'document_number'   =>  'required|string|max:11',
      'email'             =>  'required|string|max:50',
      'address'           =>  'required|string|max:60',
      'phone'             =>  'required|string|max:9',
      'status'            =>  'required|in:1,0',
    ];
  }
  
}