<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'order_id',
        'size_id'

    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

}
