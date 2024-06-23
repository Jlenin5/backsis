<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleOrdersModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;

    protected $table = 'sale_orders';

    protected $fillable = [
        'code',
        'warehouse_id',
        'customer_id',
        'employee_id',
        'date',
        'due_date',
        'tax',
        'tax_total',
        'shipping',
        'discount',
        'sub_total',
        'total',
        'status',
        'is_approved',
        'date_approved',
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

    public function sale_order_details() {
        return $this->hasMany(SaleOrderDetailsModel::class, 'sale_order_id');
    }

    public function warehouse() {
        return $this->belongsTo(WarehousesModel::class);
    }

    public function client() {
        return $this->belongsTo(ClientsModel::class);
    }

    public function employee() {
        return $this->belongsTo(EmployeeModel::class);
    }
}