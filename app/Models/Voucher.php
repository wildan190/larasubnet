<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    // Menentukan kolom yang bisa diisi secara massal
    protected $fillable = [
        'name',
        'voucher_code',
        'description',
        'size',
        'duration',
        'price',
        'isSold',
    ];

    // Menentukan tipe data untuk beberapa kolom jika diperlukan
    protected $casts = [
        'isSold' => 'boolean',
        'price' => 'decimal:2',
        'duration' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
