<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanDetail extends Model
{
    use HasFactory;
    protected $table = 'pengiriman_detail';
    protected $primaryKey = 'id_pengiriman_detail';

    protected $guarded = [];
}
