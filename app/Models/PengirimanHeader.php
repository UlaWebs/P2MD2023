<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanHeader extends Model
{
    use HasFactory;
    protected $table = 'pengiriman_header';
    protected $primaryKey = 'id_pengiriman_header';

    protected $guarded = [];
}
