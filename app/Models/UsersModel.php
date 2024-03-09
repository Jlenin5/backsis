<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model {
    protected $table = 'Users';
    protected $primaryKey = 'id';
    use HasFactory;

    protected $fillable = [
        'id',
        'Employee',
        'userDisplayName',
        'userPassword',
        'Rol',
        'uuid',
    ];

    public function employees() {
        return $this->belongsTo(EmployeeModel::class, 'Employee', 'id');
    }

    public function roles() {
        return $this->belongsTo(RolesModel::class, 'Rol', 'id');
    }
}