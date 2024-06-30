<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;

class PermissionsModel extends SpatiePermission {

    use BaseModelFilter;

    protected $table = 'permissions';

    protected $hidden = [
        'pivot',
        'guard_name',
        'created_at',
        'updated_at',
    ];

    // protected $fillable = [
    //     'id',
    //     'Rol',
    //     'NamesMenu',
    // ];
    
    // public function role() {
    //     return $this->belongsTo(RolesModel::class, 'Rol');
    // }

    // public function menuName() {
    //     return $this->belongsTo(NamesMenuModel::class, 'NamesMenu');
    // }
}
