<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananPenjualanDetail extends Model
{
    use HasFactory;
    protected $table = 'pemesanan_penjualan_detail';
    protected $primaryKey = 'id_pemesanan_penjualan_detail';

    protected $guarded = [];
}
