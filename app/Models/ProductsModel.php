<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model {
    protected $table = 'Products';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'SerialNumber',
        'prodNumber',
        'featuredImageId',
        'prodName',
        'prodDescription',
        'Unit',
        'prodStock',
        'prodPurchasePrice',
        'prodSalePrice',
        'prodWidth',
        'prodHeight',
        'prodDepth',
        'prodWeight',
        'prodState',
        'prodWebHome',
        'prodCreatedAt',
        'prodUpdatedAt',
    ];

    public function categories() {
        return $this->belongsToMany(CategoriesModel::class, 'ProductCategory', 'Product', 'Category')->withPivot('Product', 'Category');
    }

    public function productImages() {
        return $this->hasMany(ProductImagesModel::class, 'Product');
    }

    public function serialNumber() {
        return $this->belongsTo(SerialNumberModel::class, 'SerialNumber', 'id');
    }

    public function unit() {
        return $this->belongsTo(ProductUnitModel::class, 'Unit', 'id');
    }

    public function branchOffices() {
        return $this->belongsToMany(BranchOfficesModel::class, 'ProductBranchOffice', 'Product', 'BranchOffice')->withPivot('Product', 'BranchOffice');
    }
}