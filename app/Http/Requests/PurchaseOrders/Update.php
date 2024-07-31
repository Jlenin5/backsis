<?php

namespace App\Http\Requests\PurchaseOrders;

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
      'company_id'          => 'required|exists:companies,id',
      'supplier_id'         => 'required|exists:suppliers,id',
      'currency_id'         => 'required|exists:currencies,id'
    ];
  }
  
}