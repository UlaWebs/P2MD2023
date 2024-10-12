<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelunasanpembelian extends Model
{
    use HasFactory;

    protected $table = 'pelunasan_pembelian';
    protected $primaryKey = 'id_pelunasan';

    protected $guarded = [];
}
