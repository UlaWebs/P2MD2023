<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduksiDetailBop extends Model
{
    use HasFactory;
    protected $table = 'produksi_detail_bop';
    protected $primaryKey = 'id_produksi_detail_bop';

    protected $guarded = [];
}