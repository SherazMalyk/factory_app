<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'user_id',
        'account_id',
        'tran_purchase_price',
        'tran_paid_amount',
        'tran_due_amount',
        'tran_description',
    ];
}
