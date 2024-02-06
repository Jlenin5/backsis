<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDetailsModel extends Model {
    protected $table = 'TicketDetails';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'Product ',
        'Ticket',
        'ticdQuantity',
        'ticdSubtotal',
        'ticdTotal',
    ];
}