<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehousesModel extends Model {

    protected $table = 'Warehouses';
    protected $primaryKey = 'id';
    use HasFactory;

    protected $fillable = [
        'id',
        'whName',
        'whPhone',
        'whEmail',
        'Department',
        'Province',
        'District',
        'whAddress',
        'User',
        'whState'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['deleted_at'];

    public function products() {
        return $this->belongsToMany(ProductsModel::class, 'ProductWarehouse', 'Warehouse', 'Product')->withPivot('Product', 'BranchOffice');
    }

    public function users() {
        return $this->belongsTo(UsersModel::class, 'User', 'id')->with('employees');
    }

    public function departments() {
        return $this->belongsTo(DepartmentsModel::class, 'Department', 'id');
    }

    public function provinces() {
        return $this->belongsTo(ProvincesModel::class, 'Province', 'id');
    }

    public function districts() {
        return $this->belongsTo(DistrictsModel::class, 'District', 'id');
    }

}