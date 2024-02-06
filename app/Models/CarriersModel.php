<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarriersModel extends Model {
    protected $table = 'Carriers';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'carrName',
        'carrPhone',
        'carrGender',
        'carrDLicence',
        'carrState',
    ];
}