<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduksiDetailBtk extends Model
{
    use HasFactory;
    protected $table = 'produksi_detail_tenaga_kerja';
    protected $primaryKey = 'id_produksi_detail_tenaga_kerja';

    protected $guarded = [];
}
