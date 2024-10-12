<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerolehanAsetDetail extends Model
{
    use HasFactory;

    protected $table = 'perolehan_aset_detail';
    protected $primaryKey = 'id_perolehan_aset_detail';

    protected $guarded = [];
}