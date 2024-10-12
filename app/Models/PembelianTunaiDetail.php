<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianTunaiDetail extends Model
{
    use HasFactory;

    protected $table = 'pembelian_tunai_detail';
    protected $primaryKey = 'id_pembelian_tunai_detail';

    protected $guarded = [];
}