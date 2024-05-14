<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkAreaModel extends Model {

    protected $table = 'work_areas';
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'status',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}