<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

class RolesModel extends SpatieRole {

    use HasFactory, SoftDeletes, BaseModelFilter;

    protected $table = 'roles';

    protected $fillable = [
        'id',
        'name',
        'guard_name',
    ];

    protected $hidden = ['guard_name','created_at', 'updated_at', 'deleted_at'];
}   