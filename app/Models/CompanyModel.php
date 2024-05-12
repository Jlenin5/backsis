<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model {

    protected $table = 'companies';
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'image',
        'name',
        'document_number',
        'email',
        'address',
        'web_site',
        'phone',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}