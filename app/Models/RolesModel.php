<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolesModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;

    protected $table = 'roles';

    protected $fillable = [
        'name',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}   