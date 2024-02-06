<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationsModel extends Model {
    protected $table = 'Quotations';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'SerialNumber',
        'qtNumber',
        'Currency',
        'Company',
        'Client',
        'Employee',
        'qtSubtotal',
        'qtIgv',
        'qtTotal',
        'qtCreatedAt',
        'qtDeletedAt',
    ];
}