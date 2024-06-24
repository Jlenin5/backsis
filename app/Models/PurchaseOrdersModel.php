<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrdersModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'code',
        'description',
        'supplier_document',
        'company_id',
        'sede_id',
        'warehouse_id',
        'supplier_id',
        'employee_id',
        'date',
        'discount',
        'sub_total',
        'total',
        'is_approved',
        'date_approved',
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

    public function purchase_order_details() {
        return $this->hasMany(PurchaseOrderDetailsModel::class, 'purchase_order_id');
    }

    public function company() {
        return $this->belongsTo(CompaniesModel::class);
    }

    public function sede() {
        return $this->belongsTo(SedesModel::class);
    }

    public function warehouse() {
        return $this->belongsTo(WarehousesModel::class);
    }

    public function supplier() {
        return $this->belongsTo(SuppliersModel::class);
    }

    public function employee() {
        return $this->belongsTo(EmployeeModel::class);
    }
}