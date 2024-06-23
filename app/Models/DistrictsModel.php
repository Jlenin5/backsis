<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictsModel extends Model {

    protected $table = 'districts';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'name',
        'province_id',
    ];
}