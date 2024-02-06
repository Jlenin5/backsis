<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientsModel extends Model {
    protected $table = "Clients";
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'cliFirstName',
        'cliSecondName',
        'DocumentType',
        'cliDocument',
        'cliEmail',
        'cliPhone',
        'cliGender',
        'cliState',
        'cliCreatedAt',
        'cliUpdatedAt',
    ];
    
    public function documentType() {
        return $this->belongsTo(DocumentTypesModel::class, 'DocumentType');
    }
}