<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduksiDetailOutput extends Model
{
    use HasFactory;
    protected $table = 'produksi_detail_output';
    protected $primaryKey = 'id_produksi_detail_output';

    protected $guarded = [];
}
