<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationsModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;
    
    protected $table = 'quotations';

    protected $fillable = [
        'code',
        'warehouse_id',
        'customer_id',
        'employee_id',
        'start_date',
        'due_date',
        'discount',
        'shipping',
        'tax_rate',
        'tax_nate',
        'sub_total',
        'total',
        'is_accepted',
        'status',
        'user_create_id',
        'user_update_id'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function user_create() {
        return $this->belongsTo(UsersModel::class, 'user_create_id')->withTrashed();
    }

    public function user_update() {
        return $this->belongsTo(UsersModel::class, 'user_update_id')->withTrashed();
    }

    public function quote_details() {
        return $this->hasMany(QuoteDetailsModel::class, 'quote_id');
    }

    public function warehouse() {
        return $this->belongsTo(WarehousesModel::class);
    }

    public function customer() {
        return $this->belongsTo(CustomerModel::class);
    }

    public function employee() {
        return $this->belongsTo(EmployeeModel::class);
    }
}