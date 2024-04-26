<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesModel extends Model {

    protected $table = 'roles';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];
}   