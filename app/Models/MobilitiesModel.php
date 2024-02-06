<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilitiesModel extends Model {
    protected $table = 'Mobilities';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'mobBrand',
        'mobPlate',
        'mobColor',
        'mobState',
    ];
}