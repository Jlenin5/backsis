<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model {
    protected $table = 'Users';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'Employee',
        'Avatar',
        'WorkArea',
        'JobPosition',
        'userDisplayName',
        'userPassword',
        'Rol',
        'uuid',
        'userCreatedAt',
        'userUpdatedAt',
    ];

    public function employees() {
        return $this->belongsTo(EmployeeModel::class, 'Employee', 'id');
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

    public function roles() {
        return $this->belongsTo(RolesModel::class, 'Rol', 'id');
    }
}