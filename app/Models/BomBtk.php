<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomBtk extends Model
{
    use HasFactory;

    protected $table = 'bom_btk';
    protected $primaryKey = 'id_bom_btk';
    protected $guarded = [];
}