<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananHeader extends Model
{
    use HasFactory;

    protected $table = 'pemesanan_header';
    protected $primaryKey = 'id_pemesanan_header';

    protected $guarded = [];
}