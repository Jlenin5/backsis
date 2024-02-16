<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationsModel extends Model {
    protected $table = 'Quotations';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'SerialNumber',
        'qtNumber',
        'Currency',
        'Company',
        'Client',
        'Employee',
        'qtSubtotal',
        'qtIgv',
        'qtTotal',
        'qtCreatedAt',
        'qtDeletedAt',
    ];

    public function products() {
        return $this->belongsToMany(ProductsModel::class, 'QuoteDetails', 'Quotation', 'Product')->withPivot('Quotation', 'Product');
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

    public function clients() {
        return $this->belongsTo(ClientsModel::class, 'Client', 'id');
    }

    public function employees() {
        return $this->belongsTo(EmployeeModel::class, 'Employee', 'id');
    }
}