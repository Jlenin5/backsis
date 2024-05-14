<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesModel extends Model {

    protected $table = 'categories';
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'status',
    ];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function products() {
        return $this->belongsToMany(ProductsModel::class, 'ProductCategory', 'Category', 'Product')->withPivot('Product', 'Category');
    }
}