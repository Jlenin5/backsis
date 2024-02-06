<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImagesModel extends Model {
    protected $table = "ProductImages";
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'primPath',
        'Product',
        'featured'
    ];

    public function products() {
        return $this->belongsTo(ProductsModel::class, 'Product', 'id');
    }
}