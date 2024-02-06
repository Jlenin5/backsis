<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxesModels extends Model {
    protected $table = 'Taxes';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'taxName',
        'taxAbbreviation',
        'taxValue',
    ];
}