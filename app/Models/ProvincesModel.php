<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvincesModel extends Model {

    protected $table = 'provinces';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'department_id',
    ];
}