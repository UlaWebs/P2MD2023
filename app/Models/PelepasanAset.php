<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelepasanAset extends Model
{
    use HasFactory;

    protected $table = 'pelepasan_aset';
    protected $primaryKey = 'id_pelepasan_aset';

    protected $guarded = [];
}