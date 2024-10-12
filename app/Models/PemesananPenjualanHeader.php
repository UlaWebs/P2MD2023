<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananPenjualanHeader extends Model
{
    use HasFactory;
    protected $table = 'pemesanan_penjualan_header';
    protected $primaryKey = 'id_pemesanan_penjualan_header';

    protected $guarded = [];
}
