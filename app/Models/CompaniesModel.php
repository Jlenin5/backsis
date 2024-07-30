<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompaniesModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;

    protected $table = 'companies';

    protected $fillable = [
        'id',
        'code',
        'image',
        'name',
        'document_number',
        'email',
        'address',
        'employee_id',
        'web_site',
        'phone',
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

    public function employee() {
        return $this->belongsTo(EmployeeModel::class);
    }

}