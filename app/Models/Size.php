<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'size_name',
        'collar_size',
        'chest_size',
        'sleeve_length',
        'cuff_size',
        'shoulder_size',
        'waist_size',
        'shirt_length',
        'legs_length',
        'description',
        'category'
    ];

    protected $casts = [
        'category' => 'string',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
