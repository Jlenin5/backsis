<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetailsModel extends Model {

    protected $table = 'purchase_order_details';
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'purchase_order_id',
        'price',
        'quantity',
        'tax_method',
        'tax_net',
        'discount_method',
        'discount',
        'total'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}