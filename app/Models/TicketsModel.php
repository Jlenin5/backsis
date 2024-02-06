<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketsModel extends Model {
    protected $table = 'Tickets';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'SerialNumber',
        'ticNumber',
        'Currency',
        'Company',
        'Client',
        'Employee',
        'ticSubtotal',
        'ticIgv',
        'ticTotal',
        'PaymentMethod',
        'DocumentStatus',
        'ticCreatedAt',
        'ticDeletedAt',
    ];
}