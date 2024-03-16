<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationsModel extends Model {
    protected $table = 'Quotations';
    protected $primaryKey = 'id';
    use HasFactory;

    protected $fillable = [
        'id',
        'SerialNumber',
        'qtNumber',
        'Currency',
        'Company',
        'BranchOffice',
        'Client',
        'User',
        'qtSubtotal',
        'qtIgv',
        'qtTotal',
        'qtCreatedAt',
        'qtDeletedAt',
    ];

    public function quoteDetails() {
        return $this->hasMany(QuoteDetailsModel::class, 'Quotation');
    }

    public function serialNumber() {
        return $this->belongsTo(SerialNumberModel::class, 'SerialNumber', 'id');
    }

    public function currencies() {
        return $this->belongsTo(CurrenciesModel::class, 'Currency', 'id');
    }

    public function companies() {
        return $this->belongsTo(CompanyModel::class, 'Company', 'id');
    }

    public function branchOffices() {
        return $this->belongsTo(BranchOfficesModel::class, 'BranchOffice', 'id');
    }

    public function clients() {
        return $this->belongsTo(ClientsModel::class, 'Client', 'id');
    }

    public function users() {
        return $this->belongsTo(UsersModel::class, 'User', 'id')->with('employees');
    }
}