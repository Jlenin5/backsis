<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchOfficesModel extends Model {
    protected $table = 'BranchOffices';
    protected $primaryKey = 'id';
    use HasFactory;

    protected $fillable = [
        'id',
        'boName',
        'boPhone',
        'boEmail',
        'Department',
        'Province',
        'District',
        'boAddress',
        'User',
        'boState'
    ];

    public function products() {
        return $this->belongsToMany(ProductsModel::class, 'ProductBranchOffice', 'BranchOffice', 'Product')->withPivot('Product', 'BranchOffice');
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