<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitModel extends Model {

    protected $table = 'units';
    use HasFactory;

    protected $fillable = [
        'id',
        'prunUnit',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}