<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_name',
        'product_status',
        'product_type',
    ];

    protected $primaryKey = 'product_id';

    public function product_details()
    {
        return $this->hasMany(ProductDetail::class, 'product_id', 'product_id');
    }
}
