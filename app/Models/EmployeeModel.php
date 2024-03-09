<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model {
    protected $table = 'Employee';
    protected $primaryKey = 'id';
    use HasFactory;

    protected $fillable = [
        'id',
        'empImage',
        'empFirstName',
        'empSecondName',
        'empSurname',
        'empSecondSurname',
        'DocumentType',
        'Avatar',
        'WorkArea',
        'JobPosition',
        'empDocument',
        'empEmail',
        'empPhone',
        'empGender',
        'empState',
    ];
 
    public function documentType() {
        return $this->belongsTo(DocumentTypesModel::class, 'DocumentType', 'id');
    }

    public function avatars() {
        return $this->belongsTo(AvatarsModel::class, 'Avatar', 'id');
    }

    public function workAreas() {
        return $this->belongsTo(WorkAreaModel::class, 'WorkArea', 'id');
    }
    
    public function jobPositions() {
        return $this->belongsTo(JobPositionModel::class, 'JobPosition', 'id');
    }
}