<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;

    protected $table = 'products';

    protected $fillable = [
        'code',
        'featured',
        'name',
        'description',
        'unit_id',
        'stock_alert',
        'purchase_price',
        'sale_price',
        'brand_id',
        'width',
        'height',
        'depth',
        'weight',
        'liters',
        'web_site',
        'status',
        'user_create_id',
        'user_update_id'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function user_create() {
        return $this->belongsTo(UsersModel::class, 'user_create_id')->withTrashed();
    }

    public function user_update() {
        return $this->belongsTo(UsersModel::class, 'user_update_id')->withTrashed();
    }

    public function categories() {
        return $this->belongsToMany(CategoriesModel::class, 'product_categories', 'product_id', 'category_id');
    }

    public function images() {
        return $this->hasMany(ProductImagesModel::class, 'product_id');
    }

    public function tax() {
        return $this->hasOne(ProductTaxesModel::class, 'product_id');
    }

    public function unit() {
        return $this->belongsTo(MeasurementUnitModel::class);
    }

    public function brand() {
        return $this->belongsTo(BrandsModel::class);
    }

    public function warehouses() {
        // return $this->belongsToMany(WarehousesModel::class, 'product_warehouse', 'product_id', 'warehouse_id')->withPivot('product_id', 'warehouse_id');
    }

    public function quotes() {
        // return $this->belongsToMany(QuotationsModel::class, 'quote_details', 'product_id', 'quotation_id')->withPivot('Quotation', 'Product');
    }
}