<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentsModel extends Model {
    
    protected $table = 'departments';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];
}