<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepresiasiAset extends Model
{
    use HasFactory;

    protected $table = 'depresiasi_aset';
    protected $primaryKey = 'id_depresiasi_aset';

    protected $guarded = [];
}