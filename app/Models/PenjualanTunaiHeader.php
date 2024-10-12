<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanTunaiHeader extends Model
{
    use HasFactory;

    protected $table = 'penjualan_tunai_header';
    protected $primaryKey = 'id_penjualan_tunai_header';

    protected $guarded = [];
}
