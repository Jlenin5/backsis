<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model {
    
    protected $table = 'employees';
    protected $primaryKey = 'id';
    use HasFactory;

    protected $fillable = [
        'id',
        'image',
        'first_name',
        'second_name',
        'surname',
        'second_surname',
        'avatar_id',
        'work_area_id',
        'job_position_id',
        'document_type',
        'document_number',
        'email',
        'phone',
        'gender',
        'status',
    ];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function avatars() {
        return $this->belongsTo(AvatarsModel::class, 'avatar_id', 'id');
    }

    public function workAreas() {
        return $this->belongsTo(WorkAreaModel::class, 'work_area_id', 'id');
    }
    
    public function jobPositions() {
        return $this->belongsTo(JobPositionModel::class, 'job_position_id', 'id');
    }
}