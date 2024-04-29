<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model {

    protected $table = 'products';
    use HasFactory;

    protected $fillable = [
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
        'web_site',
        'status',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function categories() {
        return $this->belongsToMany(CategoriesModel::class, 'product_category', 'product_id', 'category_id')->withPivot('product_id', 'category_id');
    }

    public function productImages() {
        return $this->hasMany(ProductImagesModel::class, 'product_id');
    }

    public function unit() {
        return $this->belongsTo(UnitModel::class, 'unit_id', 'id');
    }

    public function warehouses() {
        return $this->belongsToMany(WarehousesModel::class, 'product_warehouse', 'product_id', 'warehouse_id')->withPivot('product_id', 'warehouse_id');
    }

    public function quotes() {
        return $this->belongsToMany(QuotationsModel::class, 'QuoteDetails', 'Product', 'Quotation')->withPivot('Quotation', 'Product');
    }
}