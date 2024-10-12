<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanTunaiDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_tunai_detail';
    protected $primaryKey = 'id_penjualan_tunai_detail';

    protected $guarded = [];
}
