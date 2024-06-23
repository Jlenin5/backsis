<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehousesModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;

    protected $table = 'warehouses';

    protected $fillable = [
        'id',
        'name',
        'phone',
        'email',
        'business_id',
        'sede_id',
        'department_id',
        'district_id',
        'province_id',
        'address',
        'employee_id',
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

    public function products() {
        return $this->belongsToMany(ProductsModel::class, 'product_warehouse', 'warehouse_id', 'product_id')->withPivot('Product', 'BranchOffice');
    }

    public function business() {
        return $this->belongsTo(BusinessesModel::class);
    }

    public function sede() {
        return $this->belongsTo(SedesModel::class);
    }

    public function employee() {
        return $this->belongsTo(EmployeeModel::class);
    }

    public function department() {
        return $this->belongsTo(DepartmentsModel::class);
    }

    public function province() {
        return $this->belongsTo(ProvincesModel::class);
    }

    public function district() {
        return $this->belongsTo(DistrictsModel::class);
    }

}