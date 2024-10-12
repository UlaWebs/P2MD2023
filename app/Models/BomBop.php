<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomBop extends Model
{
    use HasFactory;

    protected $table = 'bom_bop';
    protected $primaryKey = 'id_bom_bop';
    protected $guarded = [];
}