<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamesMenuModel extends Model {
    protected $table = 'NamesMenu';
    protected $primaryKey = 'menuId';
    public $timestamps = false;
    use HasFactory;
}