<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuppliersModel extends Model {

    protected $table = 'suppliers';
    use HasFactory;

    protected $fillable = [
        'id',
        'document_type',
        'document_number',
        'name',
        'email',
        'address',
        'phone',
        'status'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}