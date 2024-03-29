<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetailsModel extends Model {
    protected $table = 'PurchaseOrderDetails';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'Product',
        'PurchaseOrder',
        'podPrice',
        'podTax',
        'podDiscountMethod',
        'podDiscount',
        'podQuantity',
        'podTotal'
    ];
}