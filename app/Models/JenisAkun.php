<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisAkun extends Model
{
    use HasFactory;
    protected $table = "jenis_akun";
    protected $primaryKey = "id_jenis_akun";
}
