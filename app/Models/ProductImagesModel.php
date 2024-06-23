<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImagesModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;
    
    protected $table = "product_image";

    protected $fillable = [
        'path',
        'product_id',
        'featured',
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

    public function products() {
        return $this->belongsTo(ProductsModel::class, 'product_id', 'id');
    }
}