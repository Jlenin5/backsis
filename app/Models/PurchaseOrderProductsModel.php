<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderProductsModel extends Model {
    
    use HasFactory, SoftDeletes, BaseModelFilter;

    protected $table = 'purchase_order_products';

    protected $fillable = [
        'id',
        'purchase_order_id',
        'product_id',
        'price',
        'tax',
        'quantity',
        'warehouse_id',
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

    public function purchase_order() {
        return $this->belongsTo(PurchaseOrdersModel::class);
    }

    public function product() {
        return $this->belongsTo(ProductsModel::class)->withTrashed();
    }

    public function warehouse() {
        return $this->belongsTo(WarehousesModel::class);
    }
}
