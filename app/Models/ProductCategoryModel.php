<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryModel extends Model {

    protected $table = "product_category";
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'category_id',
    ];
    
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    
}