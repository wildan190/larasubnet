<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_status',
        'action',
        'notification',
    ];

    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
