<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomBbb extends Model
{
    use HasFactory;

    protected $table = 'bom_bbb';
    protected $primaryKey = 'id_bom_bbb';

    protected $guarded = [];
}