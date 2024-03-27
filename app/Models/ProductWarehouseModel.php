<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWarehouseModel extends Model {
    protected $table = 'ProductWarehouse';
    protected $primaryKey = 'id';
    use HasFactory;

    protected $fillable = [
        'id',
        'Product',
        'Warehouse',
        'pwQuantity',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['deleted_at'];
    
}