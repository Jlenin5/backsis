<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrdersModel extends Model {
    protected $table = 'PurchaseOrders';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'SerialNumber',
        'puorNumber',
        'Currency',
        'Company',
        'BranchOffice',
        'Supplier',
        'User',
        'puorStartDate',
        'puorEndDate',
        'puorSubtotal',
        'puorTax',
        'puorTotal'
    ];

    public function purchaseOrderDetails() {
        return $this->hasMany(PurchaseOrderDetailsModel::class, 'PurchaseOrder');
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

    public function suppliers() {
        return $this->belongsTo(SuppliersModel::class, 'Supplier', 'id');
    }

    public function users() {
        return $this->belongsTo(UsersModel::class, 'User', 'id')->with('employees');
    }
}