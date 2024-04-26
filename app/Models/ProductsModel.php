<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model {

    protected $table = 'products';
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'featured',
        'name',
        'description',
        'unit_id',
        'stock_alert',
        'purchase_price',
        'sale_price',
        'width',
        'height',
        'depth',
        'weight',
        'web',
        'status',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

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