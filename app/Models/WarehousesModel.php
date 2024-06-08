<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehousesModel extends Model {

    protected $table = 'warehouses';
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'phone',
        'email',
        'branch_office_id',
        'department_id',
        'province_id',
        'district_id',
        'address',
        'employee_id',
        'status'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function products() {
        return $this->belongsToMany(ProductsModel::class, 'ProductWarehouse', 'Warehouse', 'Product')->withPivot('Product', 'BranchOffice');
    }

    public function branch_office() {
        return $this->belongsTo(BranchOfficesModel::class, 'branch_office_id', 'id');
    }

    public function employee() {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'id');
    }

    public function department() {
        return $this->belongsTo(DepartmentsModel::class, 'department_id', 'id');
    }

    public function province() {
        return $this->belongsTo(ProvincesModel::class, 'province_id', 'id');
    }

    public function district() {
        return $this->belongsTo(DistrictsModel::class, 'district_id', 'id');
    }

}