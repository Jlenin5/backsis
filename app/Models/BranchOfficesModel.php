<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchOfficesModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;

    protected $table = 'branch_offices';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'company_id',
        'department_id',
        'province_id',
        'district_id',
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

    public function company() {
        return $this->belongsTo(CompaniesModel::class);
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