<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_type',
        'transaction_status',
        'fraud_status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
