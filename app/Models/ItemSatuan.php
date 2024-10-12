<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSatuan extends Model
{
    use HasFactory;

    protected $table = 'item_satuan';
    protected $primaryKey = 'id_item_satuan';

    protected $guarded = [];
}
