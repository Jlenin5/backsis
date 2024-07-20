<?php

namespace App\Http\Requests\Suppliers;

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
      'document'          =>  'required|string|max:11',
      'name'              =>  'required|string|max:50',
      'address'           =>  'required|string|max:150',
      'status'            =>  'required|in:1,0',
    ];
  }
  
}