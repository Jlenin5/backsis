<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrderDetailsModel extends Model {
    
    protected $table = 'sale_order_details';
    use HasFactory;

    protected $fillable = [
        'id',
        'product_name',
        'product_id',
        'sale_order_id',
        'price',
        'quantity',
        'discount_method',
        'discount',
        'total'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function sale_order() {
        return $this->belongsTo(SaleOrdersModel::class);
    }
}