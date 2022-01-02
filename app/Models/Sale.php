<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'sale_price',
        'sale_description',
        'created_by',
        'product_id',        
    ];

    /* protected $primaryKey = 'sale_id';

    public function sale_details()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id', 'sale_id');
    } */
}
