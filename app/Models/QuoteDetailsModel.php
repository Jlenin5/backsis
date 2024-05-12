<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteDetailsModel extends Model {

    protected $table = 'quote_details';
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'quote_id',
        'qtdQuantity',
        'qtdSubtotal',
        'qtdTotal',
    ];
}