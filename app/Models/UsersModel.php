<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class UsersModel extends Authenticatable {

    use Notifiable, HasFactory,  SoftDeletes, HasRoles, BaseModelFilter;
    
    protected $table = 'users';

    protected $fillable = [
        'employee_id',
        'display_name',
        'display_email',
        'password',
        'rol_id',
        'uuid',
        'status',
        'user_create_id',
        'user_update_id'
    ];

    protected $hidden = ['created_at','updated_at','deleted_at','password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['license'];

    public function getJWTCustomClaims() {
        return [];
    }

    public function employee() {
        return $this->belongsTo(EmployeeModel::class)->with('avatar');
    }

    public function rol() {
        return $this->belongsTo(RolesModel::class);
    }
    
    public function getLicenseAttribute() {
        return SettingsModel::where('key', 'license')->first()->value ?? 'INTEGRAL';
    }
}