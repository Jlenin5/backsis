<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientsModel extends Model {

    protected $table = "clients";
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'document_type',
        'document_number',
        'email',
        'phone',
        'gender',
        'status'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

}