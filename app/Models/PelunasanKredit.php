<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelunasanKredit extends Model
{
    use HasFactory;

    protected $table = 'pelunasan_kredit';
    protected $primaryKey = 'id_pelunasan_kredit';

    protected $guarded = [];
}
