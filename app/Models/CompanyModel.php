<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model {
    protected $table = 'Company';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'comCode',
        'comImage',
        'comName',
        'comRUC',
        'comEmail',
        'comAddress',
        'comWebSite',
        'comPhone',
    ];
}