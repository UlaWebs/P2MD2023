<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalHeader extends Model
{
    use HasFactory;

    protected $table = 'jurnal_header';
    protected $primaryKey = 'id_jurnal_header';

    protected $guarded = [];
}