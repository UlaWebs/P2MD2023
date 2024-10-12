<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akundefault extends Model
{
    use HasFactory;

    protected $table = 'akun_default'; //memperjelas menggunakan tabel apa
    protected $primaryKey = 'id_akun_default'; //memperjelas PK

    protected $guarded = [];
}
