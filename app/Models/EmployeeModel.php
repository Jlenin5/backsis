<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;
    
    protected $table = 'employees';

    protected $fillable = [
        'image',
        'first_name',
        'second_name',
        'surname',
        'second_surname',
        'company_id',
        'branch_office_id',
        'warehouse_id',
        'avatar_id',
        'work_area_id',
        'job_position_id',
        'document_type',
        'document_number',
        'email',
        'phone',
        'gender',
        'status',
        'user_create_id',
        'user_update_id'
    ];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function user_create() {
        return $this->belongsTo(UsersModel::class, 'user_create_id')->withTrashed();
    }

    public function user_update() {
        return $this->belongsTo(UsersModel::class, 'user_update_id')->withTrashed();
    }

    public function company() {
        return $this->belongsTo(CompaniesModel::class);
    }

    public function branch_office() {
        return $this->belongsTo(BranchOfficesModel::class);
    }
    
    public function warehouse() {
        return $this->belongsTo(WarehousesModel::class);
    }

    public function avatar() {
        return $this->belongsTo(AvatarsModel::class);
    }

    public function work_area() {
        return $this->belongsTo(WorkAreaModel::class);
    }
    
    public function job_position() {
        return $this->belongsTo(JobPositionModel::class);
    }
    
}