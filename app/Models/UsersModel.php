<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model {
    
    protected $table = 'users';
    protected $primaryKey = 'id';
    use HasFactory;

    protected $fillable = [
        'id',
        'employee_id',
        'display_name',
        'password',
        'rol_id',
        'uuid',
    ];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function employees() {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'id')->with('avatars');
    }

    public function roles() {
        return $this->belongsTo(RolesModel::class, 'rol_id', 'id');
    }
}