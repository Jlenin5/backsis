<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model {
    
    protected $table = 'employees';
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