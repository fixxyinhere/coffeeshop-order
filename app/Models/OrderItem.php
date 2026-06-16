<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'menu_item_id',
        'menu_item_name',
        'menu_item_price',
        'quantity',
        'subtotal',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'menu_item_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function options()
    {
        return $this->hasMany(OrderItemOption::class);
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->menu_item_price, 0, ',', '.');
    }
}
