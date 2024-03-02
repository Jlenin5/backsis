<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model {
    protected $table = 'Employee';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'empFirstName',
        'empSecondName',
        'DocumentType',
        'empDocument',
        'empEmail',
        'empPhone',
        'empGender',
        'empState',
        'empCreatedAt',
        'empUpdatedAt',
    ];
 
    public function documentType() {
        return $this->belongsTo(DocumentTypesModel::class, 'DocumentType', 'id');
    }
}