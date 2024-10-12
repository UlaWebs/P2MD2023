<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranBiaya extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_biaya';
    protected $primaryKey = 'id_pengeluaran_biaya';

    protected $guarded = [];
}