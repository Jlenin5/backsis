<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWarehouseModel extends Model {
    
    protected $table = 'product_warehouse';
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'warehouse_id',
        'quantity',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    
}