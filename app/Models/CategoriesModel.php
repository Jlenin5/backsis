<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesModel extends Model {
    protected $table = 'Categories';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'cateName',
        'cateState',
    ];

    public function products() {
        return $this->belongsToMany(ProductsModel::class, 'ProductCategory', 'Category', 'Product')->withPivot('Product', 'Category');
    }
}