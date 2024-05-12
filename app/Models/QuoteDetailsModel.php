<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteDetailsModel extends Model {

    protected $table = 'quote_details';
    use HasFactory;

    protected $fillable = [
        'id',
        'product_name',
        'product_price',
        'product_id',
        'quote_id',
        'quantity',
        'sub_total',
        'total',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function quotation() {
        return $this->belongsTo(QuotationsModel::class, 'quote_id');
    }

    public function product() {
        return $this->belongsTo(ProductsModel::class, 'product_id');
    }
}