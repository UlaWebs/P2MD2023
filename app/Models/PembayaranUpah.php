<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranUpah extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_upah';
    protected $primaryKey = 'id_pembayaran_upah';

    protected $guarded = [];
}
