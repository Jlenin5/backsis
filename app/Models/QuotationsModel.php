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
        'id',
        'code',
        'description',
        'customer_id',
        'currency_id',
        'exchange_rate',
        'approved',
        'user_approved_id',
        'status',
        'user_create_id',
        'user_update_id',
        'created_at'
    ];

    protected $hidden = ['updated_at', 'deleted_at'];

    public function user_create() {
        return $this->belongsTo(UsersModel::class, 'user_create_id')->withTrashed();
    }

    public function user_update() {
        return $this->belongsTo(UsersModel::class, 'user_update_id')->withTrashed();
    }

    public function user_approved() {
        return $this->belongsTo(UsersModel::class, 'user_approved_id');
    }

    public function product_quotations() {
        return $this->hasMany(ProductQuotationsModel::class, 'quotation_id');
    }

    public function customer() {
        return $this->belongsTo(CustomerModel::class);
    }

    public function currency() {
        return $this->belongsTo(CurrenciesModel::class);
    }
}