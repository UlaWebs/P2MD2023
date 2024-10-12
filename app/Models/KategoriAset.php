<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAset extends Model
{
    use HasFactory;

    protected $table = 'kategori_aset'; //memperjelas menggunakan tabel apa
    protected $primaryKey = 'id_kategori_aset'; //memperjelas PK

    protected $guarded = [];
}