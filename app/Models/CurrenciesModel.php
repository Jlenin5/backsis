<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrenciesModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;
    
    protected $table = 'currencies';

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'status',
        'user_create_id',
        'user_update_id'
    ];

    protected $hidden = ['created_at','updated_at','deleted_at'];
}