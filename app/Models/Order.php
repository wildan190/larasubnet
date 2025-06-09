<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['voucher_id', 'order_number', 'order_date', 'total_price', 'status', 'customer_name', 'customer_email'];

    // Relationship with Voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    // Relationship with Transaction
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
