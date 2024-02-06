<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicesModel extends Model {
    protected $table = 'Invoices';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'SerialNumber',
        'invNumber',
        'Currency',
        'Company',
        'Client',
        'Employee',
        'invSubtotal',
        'invIgv',
        'invTotal',
        'PaymentMethod',
        'DocumentStatus',
        'invCreatedAt',
        'invDeletedAt',
    ];
}