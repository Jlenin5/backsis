<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleOrderDetailsModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;
    
    protected $table = 'sale_order_details';

    protected $fillable = [
        'product_name',
        'product_id',
        'sale_order_id',
        'price',
        'quantity',
        'discount_method',
        'discount',
        'total',
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

    public function sale_order() {
        return $this->belongsTo(SaleOrdersModel::class);
    }
}