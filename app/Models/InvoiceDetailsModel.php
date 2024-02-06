<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetailsModel extends Model {
    protected $table = 'InvoiceDetails';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'Product ',
        'Invoice',
        'invdQuantity',
        'invdSubtotal',
        'invdTotal',
    ];
}