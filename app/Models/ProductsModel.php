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

    public function warehouses() {
        return $this->belongsToMany(WarehousesModel::class, 'ProductWarehouse', 'Product', 'Warehouse')->withPivot('Product', 'Warehouse');
    }

    public function quotes() {
        return $this->belongsToMany(QuotationsModel::class, 'QuoteDetails', 'Product', 'Quotation')->withPivot('Quotation', 'Product');
    }
}