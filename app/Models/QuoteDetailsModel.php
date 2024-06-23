<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuoteDetailsModel extends Model {
    
    use HasFactory, SoftDeletes, BaseModelFilter;

    protected $table = 'quote_details';

    protected $fillable = [
        'product_name',
        'product_price',
        'product_id',
        'quote_id',
        'quantity',
        'sub_total',
        'total',
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

    public function quotation() {
        return $this->belongsTo(QuotationsModel::class, 'quote_id');
    }

    public function product() {
        return $this->belongsTo(ProductsModel::class, 'product_id');
    }
}