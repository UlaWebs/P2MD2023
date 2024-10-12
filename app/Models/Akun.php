<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory;

    protected $table = 'akun'; //memperjelas menggunakan tabel apa
    protected $primaryKey = 'id_akun'; //memperjelas PK

    protected $guarded = [];
}
