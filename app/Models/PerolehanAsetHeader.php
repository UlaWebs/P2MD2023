<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerolehanAsetHeader extends Model
{
    use HasFactory;

    protected $table = 'perolehan_aset_header';
    protected $primaryKey = 'id_perolehan_aset_header';

    protected $guarded = [];
}