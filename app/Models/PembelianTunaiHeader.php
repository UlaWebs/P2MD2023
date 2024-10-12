<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianTunaiHeader extends Model
{
    use HasFactory;

    protected $table = 'pembelian_tunai_header';
    protected $primaryKey = 'id_pembelian_tunai_header';

    protected $guarded = [];
}