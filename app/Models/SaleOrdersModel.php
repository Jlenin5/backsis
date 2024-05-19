<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrdersModel extends Model {

    protected $table = 'sale_orders';
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'warehouse_id',
        'client_id',
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
        'date_approved'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function sale_order_details() {
        return $this->hasMany(SaleOrderDetailsModel::class, 'sale_order_id');
    }

    public function warehouses() {
        return $this->belongsTo(WarehousesModel::class, 'warehouse_id', 'id');
    }

    public function clients() {
        return $this->belongsTo(ClientsModel::class, 'client_id', 'id');
    }

    public function employees() {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'id');
    }
}