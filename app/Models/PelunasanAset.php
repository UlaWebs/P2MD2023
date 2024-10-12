<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelunasanAset extends Model
{
    use HasFactory;

    protected $table = 'pelunasan_aset';
    protected $primaryKey = 'id_pelunasan_aset';

    protected $guarded = [];
}