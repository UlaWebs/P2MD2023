<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanDetail extends Model
{
    use HasFactory;

    protected $table = 'penerimaan_detail';
    protected $primaryKey = 'id_penerimaan_detail';

    protected $guarded = [];
}
