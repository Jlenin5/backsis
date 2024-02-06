<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionsModel extends Model {

    protected $table = 'Permissions';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'Rol',
        'NamesMenu',
    ];
    
    public function role() {
        return $this->belongsTo(RolesModel::class, 'Rol');
    }

    public function menuName() {
        return $this->belongsTo(NamesMenuModel::class, 'NamesMenu');
    }
}
