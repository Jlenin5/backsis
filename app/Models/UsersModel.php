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

    public function humanResources() {
        return $this->belongsTo(EmployeeModel::class, 'Employee');
    }
    
    public function roles() {
        return $this->belongsTo(RolesModel::class, 'Rol');
    }
}