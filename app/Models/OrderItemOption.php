<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemOption extends Model
{
    protected $fillable = [
        'order_item_id',
        'option_group',
        'option_value',
        'price_modifier',
    ];

    protected function casts(): array
    {
        return [
            'price_modifier' => 'decimal:2',
        ];
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
