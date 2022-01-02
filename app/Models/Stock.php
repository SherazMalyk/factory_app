<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'layer_id',
        'part_id',
        'stock_qty',
        'stock_unit',
        'stock_price',
        'supplier_id',
        'created_by',
        'stock_description',
        'stock_status',
    ];
}
 