<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduksiDetailBahanBaku extends Model
{
    use HasFactory;
    protected $table = 'produksi_detail_bhn_baku';
    protected $primaryKey = 'id_produksi_detail_bhn_baku';

    protected $guarded = [];
}
