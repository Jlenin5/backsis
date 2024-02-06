<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentContentModel extends Model {
    protected $table = 'DocumentContent';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id',
        'docoHeader',
        'docoHeaderMarked',
        'docoFooter',
        'docoFooterMarked',
    ];
}