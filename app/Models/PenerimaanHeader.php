<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanHeader extends Model
{
    use HasFactory;

    protected $table = 'penerimaan_header';
    protected $primaryKey = 'id_penerimaan_header';

    protected $guarded = [];
}