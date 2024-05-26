<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTaxesModel extends Model {
    
    protected $table = "product_taxes";
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'igv',
        'isc',
        'igv_value',
        'isc_value'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

}
