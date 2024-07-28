<?php

namespace App\Http\Requests\Products;

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
   * Prepare the data for validation.
   */
  protected function prepareForValidation() {
    $productData = json_decode($this->input('productData'), true);

    $this->merge($productData);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules() {
    return [
      'name'               =>  'required|string|max:60',
      'stock_alert'        =>  'required|integer|max:10',
      'web_site'           =>  'required|in:1,0',
      'status'             =>  'required|in:1,0'
    ];
  }
  
}