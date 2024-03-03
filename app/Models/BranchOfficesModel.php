<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchOfficesModel extends Model {
    protected $table = 'BranchOffices';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'boName',
        'boPhone',
        'boEmail',
        'District',
        'boAddress',
        'User',
        'boState',
        'boCreatedAt',
        'boUpdatedAt',
    ];

    public function products() {
        return $this->belongsToMany(ProductsModel::class, 'ProductBranchOffice', 'BranchOffice', 'Product')->withPivot('Product', 'BranchOffice');
    }

    public function users() {
        return $this->belongsTo(UsersModel::class, 'User', 'id')->with('employees');
    }

    public function districts() {
        return $this->belongsTo(DistrictsModel::class, 'District', 'id');
    }

}