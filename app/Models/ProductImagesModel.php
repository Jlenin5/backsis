<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImagesModel extends Model {
    
    protected $table = "product_image";
    use HasFactory;

    protected $fillable = [
        'id',
        'path',
        'product_id',
        'featured'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function products() {
        return $this->belongsTo(ProductsModel::class, 'product_id', 'id');
    }
}