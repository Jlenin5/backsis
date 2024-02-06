<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuppliersModel extends Model {
    protected $table = 'Suppliers';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'DocumentType',
        'suppDocument',
        'suppCompanyName',
        'suppEmail',
        'suppPhone',
        'suppState',
        'suppCreatedAt',
        'suppUpdatedAt',
    ];

    public function documentType() {
        return $this->belongsTo(DocumentTypesModel::class, 'DocumentType');
    }
}