<?php

namespace App\Http\Requests\Employees;

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
      'first_name'         =>  'required|string',
      'surname'            =>  'required|string',
      'second_surname'     =>  'required|string',
      'document_type'      =>  'required|in:1,0',
      'document_number'    =>  'required|string',
      'gender'             =>  'required|in:1,0',
      'status'             =>  'required|in:1,0'
    ];
  }
  
}