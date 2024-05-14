<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationsModel extends Model {
    
    protected $table = 'quotations';
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'warehouse_id',
        'client_id',
        'employee_id',
        'start_date',
        'due_date',
        'discount',
        'shipping',
        'tax_rate',
        'tax_nate',
        'sub_total',
        'total',
        'status',
        'is_accepted'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function quote_details() {
        return $this->hasMany(QuoteDetailsModel::class, 'quote_id');
    }

    public function warehouses() {
        return $this->belongsTo(WarehousesModel::class, 'warehouse_id', 'id');
    }

    public function clients() {
        return $this->belongsTo(ClientsModel::class, 'client_id', 'id');
    }

    public function employees() {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'id');
    }
}