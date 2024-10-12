<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranAset extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_aset';
    protected $primaryKey = 'id_pengeluaran_aset';

    protected $guarded = [];
}