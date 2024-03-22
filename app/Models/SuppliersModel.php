<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuppliersModel extends Model {
    protected $table = 'Suppliers';
    protected $primaryKey = 'id';
    use HasFactory;

    protected $fillable = [
        'id',
        'DocumentType',
        'suppDocument',
        'suppCompanyName',
        'suppEmail',
        'suppAddress',
        'suppPhone',
        'suppState',
    ];

    public function documentType() {
        return $this->belongsTo(DocumentTypesModel::class, 'DocumentType');
    }
}