<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemOption extends Model
{
    protected $fillable = [
        'menu_item_id',
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

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
