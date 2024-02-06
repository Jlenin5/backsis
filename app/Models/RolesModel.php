<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesModel extends Model {
    protected $table = 'Roles';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'rolName',
    ];
}   