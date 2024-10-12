<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduksiHeader extends Model
{
    use HasFactory;

    protected $table = 'produksi_header';
    protected $primaryKey = 'id_produksi_header';

    protected $guarded = [];
}
