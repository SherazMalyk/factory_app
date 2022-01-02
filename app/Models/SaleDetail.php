<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_qty',
        'stock_id',
        'layer_id',        
        'part_id',        
        'sale_id',        
    ];
}
