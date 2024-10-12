<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;

    protected $table = 'aset'; //memperjelas menggunakan tabel apa
    protected $primaryKey = 'id_aset'; //memperjelas PK

    protected $guarded = [];
}