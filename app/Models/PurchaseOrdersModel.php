<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrdersModel extends Model {

    protected $table = 'purchase_orders';
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'warehouse_id',
        'supplier_id',
        'employee_id',
        'supplier_document',
        'date',
        'discount',
        'sub_total',
        'total',
        'status',
        'is_approved',
        'date_approved'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function purchase_order_details() {
        return $this->hasMany(PurchaseOrderDetailsModel::class, 'purchase_order_id');
    }

    public function warehouses() {
        return $this->belongsTo(WarehousesModel::class, 'warehouse_id', 'id');
    }

    public function suppliers() {
        return $this->belongsTo(SuppliersModel::class, 'supplier_id', 'id');
    }

    public function employees() {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'id');
    }
}