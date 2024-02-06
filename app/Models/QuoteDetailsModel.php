<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteDetailsModel extends Model {
    protected $table = 'QuoteDetails';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'Product',
        'Quotation',
        'qtdQuantity',
        'qtdSubtotal',
        'qtdTotal',
    ];
}